<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Custom\StockBalanceFunction;
use App\Product;
use App\Stocktransferhistory;

class StockTransferController extends Controller {

    public function stockTransfer() {
        $products = Product::orderBy('product_name')
                ->get();

        return view('pages.admin-side.modules.stockopname.stocktransfer')->with([
                    'products' => $products
        ]);
    }

    public function transfer(Request $request) {
        $this->validate($request, [
            'produk' => 'required',
            'jumlah' => 'required'
        ]);

        #pindahin stok reserved ke utama
        $product = Product::find($request->produk);
        if (!$product) {
            return back()->with([
                        'err' => 'Tidak ada produk yang ditemukan.'
            ]);
        }

        #cek apakah kalau dipindahin, stok cadangan minus
        if ($product->reserved_qty - $request->jumlah < 0) {
            return back()->with([
                        'err' => 'Transfer Gagal. Stok akan minus.'
            ]);
        }

        $initial_qty = $product->qty;
        $initial_reserved_qty = $product->reserved_qty;
        
        #transfer
        StockBalanceFunction::addBalance($product->id, $request->jumlah, 0, 0, "Transfer Stok dari Stok Cadangan");
        $product->qty += $request->jumlah;
        $product->reserved_qty -= $request->jumlah;
        $product->save();
        
        #simpan perubahan itu ke history
        #TODO
        $history = new Stocktransferhistory;
        $history->product_id = $product->id;
        $history->initial_qty = $initial_qty;
        $history->current_qty = $product->qty;
        $history->transfer_qty = $request->jumlah;
        $history->initial_reserved_qty = $initial_reserved_qty;
        $history->current_reserved_qty = $product->reserved_qty;
        $history->transfer_reserved_qty = $request->jumlah;
        $history->save();

        return back()->with([
                    'msg' => 'Produk: ' . $product->product_name . ' telah ditransfer stok utama sebanyak: ' . $request->jumlah . '.'
        ]);
    }

}
