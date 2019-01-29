<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Custom\StockBalanceFunction;
use App\Http\Controllers\Custom\ExportFunction;
use App\Product;
use App\Stockrevise;
use Excel;

class StockReviseController extends Controller {

    public function stockRevise() {
        #ambil user_id
        $user_id = auth()->user()->id;

        #Ambil list revisi berdasarkan yang user request
        $revise_list = Stockrevise::where('user_id', '=', $user_id)
                ->orderBy('created_at', 'desc')
                ->get();

        return view('pages.admin-side.modules.stockrevise.stockrevise')->with([
                    'revise_list' => $revise_list
        ]);
    }

    public function stockReviseList() {
        #Ambil seluruh list revisi
        $revise_list = Stockrevise::orderBy('created_at', 'desc')
                ->get();

        return view('pages.admin-side.modules.stockrevise.stockreviselist')->with([
                    'revise_list' => $revise_list
        ]);
    }

    public function stockReviseForm() {
        $products = Product::orderBy('product_name')
                ->get();

        return view('pages.admin-side.modules.stockrevise.revisestock')->with([
                    'products' => $products
        ]);
    }

    public function reviseStock(Request $request) {
        $this->validate($request, [
            'product' => 'required',
            'stok' => 'required|numeric|min:0',
            'catatan' => 'required|min:0'
        ]);

        #ambil data produk saat ini
        $product = Product::find($request->product);
        if (!$product) {
            return back()->with([
                        'err' => 'Produk tidak ditemukan.'
            ]);
        }

        #cek apakah stok perubahan sama dengan stok utama
        if ($product->qty == $request->stok) {
            return back()->with([
                        'err' => 'Tidak ada perubahan karena jumlah stok sama.'
            ]);
        }

        #simpan ke stockrevise
        $stock_revise = new Stockrevise;
        $stock_revise->user_id = auth()->user()->id;
        $stock_revise->product_id = $request->product;
        $stock_revise->initial_qty = $product->qty;
        $stock_revise->change_qty = $request->stok - $product->qty;
        $stock_revise->current_qty = $request->stok;
        $stock_revise->notes = $request->catatan;
        $stock_revise->save();

        #simpan ke history
        $stock_in = 0;
        $stock_out = 0;
        if($product->qty < $request->stok){
            $stock_in = $request->stok - $product->qty;
        }else{
            $stock_out = $product->qty - $request->stok;
        }
        StockBalanceFunction::addBalance($product->id, $stock_in, 0, $stock_out, "Revisi Stok Satuan. ID: " . $stock_revise->id);

        #ubah stok utama jadi stok yang diubah tersebut
        $product->qty = $request->stok;
        $product->save();

        return redirect('stockrevise')->with([
                    'msg' => 'Produk: ' . $product->product_name . ' saat ini berhasil diubah stok utamanya menjadi: ' . $product->qty . '.<br>Apabila tidak disetujui, maka stok ini akan divoid oleh admin.'
        ]);
    }

    public function downloadFormat() {
        ExportFunction::exportForImportStockRevise();
    }

    public function reviseBulk(Request $request) {
        $this->validate($request, [
            'file' => 'required'
        ]);

        //inisialisasi data
        $file = $request['file'];
        $filesize = $file->getSize(); //hasil dalam satuan bytes..
        //Validasi ukuran file
        //Ukuran file > 0B
        if ($filesize <= 0) {
            return back()->with('err', 'File tidak ada data..');
        }

        //Validasi extension file
        //Extension file harus .xls atau .xlsx
        if ($file->getClientOriginalExtension() != 'xls' && $file->getClientOriginalExtension() != 'xlsx') {
            return back()->with('err', 'File harus .xls atau .xlss');
        }


        echo '<h1>Hasil Import :</h1>';

        Excel::selectSheets('Sheet1')->load($file, function($reader) {

            //Baca smua sheets
            $reader->each(function($row) {

                if (ctype_space($row->id) || $row->id == null || $row->id == '') {
                    echo '<b style="color:red">ID jangan dikosongkan</b><br /><br />';
                } else if (ctype_digit($row->ubah_stok_utama)) {
                    echo '<b style="color:red">"Ubah Stok Utama" Tidak Diisi pada produk dengan Nama ' . $row->product_name . '. TIDAK DIPROSES</b><br /><br />';
                } else if (ctype_space($row->catatan) || $row->catatan == null || $row->catatan == '') {
                    echo '<b style="color:red">"Catatan" Tidak Diisi pada produk dengan Nama ' . $row->product_name . '. TIDAK DIPROSES</b><br /><br />';
                } else {

                    #kalau ubah stok utama = 0, lewatkan langsung
                    if ($row->ubah_stok_utama != 0) {
                        $product = Product::find($row->id);

                        //Kalau tidak ada produknya, abaikan..
                        if ($product == null) {
                            echo '<b style="color:red">Produk dengan Nama : ' . $row->product_name . ' tidak terdaftar. TIDAK DIPROSES</b><br /><br />';
                        } else {

                            #cek kalau stok tetap sama, tidak perlu update
                            if ($product->qty == $row->ubah_stok_utama) {
                                echo '<b style="color:red">Produk dengan Nama : ' . $row->product_name . ' tidak diubah stok utama karena jumlah sama. TIDAK DIPROSES</b><br /><br />';
                            } else {
                                #simpan ke stockrevise
                                $stock_revise = new Stockrevise;
                                $stock_revise->user_id = auth()->user()->id;
                                $stock_revise->product_id = $row->id;
                                $stock_revise->initial_qty = $product->qty;
                                $stock_revise->change_qty = $row->ubah_stok_utama - $product->qty;
                                $stock_revise->current_qty = $row->ubah_stok_utama;
                                $stock_revise->notes = $row->catatan;
                                $stock_revise->save();

                                #simpan ke history
                                $stock_in = 0;
                                $stock_out = 0;
                                if ($product->qty < $row->ubah_stok_utama) {
                                    $stock_in = $row->ubah_stok_utama - $product->qty;
                                } else {
                                    $stock_out = $product->qty - $row->ubah_stok_utama;
                                }
                                StockBalanceFunction::addBalance($product->id, $stock_in, 0, $stock_out, "Revisi Stok Excel. ID: " . $stock_revise->id);

                                #ubah stok utama jadi stok yang diubah tersebut
                                $product->qty = $row->ubah_stok_utama;
                                $product->save();

                                echo '<b style="color:blue">Produk dengan ID : ' . $row->id . ' SELESAI DIPROSES.<br />Stok saat ini menjadi: ' . $product->qty . '.</b><br /><br />';
                            }
                        }
                    } else {
                        echo '<b style="color:orange">Produk dengan ID : ' . $row->id . ' TIDAK DIPROSES.</b><br /><br />';
                    }
                }
            });
        });

        echo '<br /><a href="' . url()->previous() . '">Kembali</a>';
    }

    public function approveRevise(Request $request) {
        $this->validate($request, [
            'stock_id' => 'required',
            'alasan_terima' => 'required'
        ]);

        #loop seluruh stok yang dicentang
        foreach ($request->stock_id as $stock) {
            #ambil data user_id
            $user_id = auth()->user()->id;

            #update semua stok jadi approve
            $stock_revise = Stockrevise::find($stock);
            if ($stock_revise) {
                $stock_revise->is_approved = 1;
                $stock_revise->approve_by = $user_id;
                $stock_revise->reason = $request->alasan_terima;
                $stock_revise->approve_time = \Carbon\Carbon::now()->toDateTimeString();
                $stock_revise->save();
            }
        }

        return back()->with([
                    'msg' => 'Seluruh stok sudah disetujui.'
        ]);
    }

    public function rejectRevise(Request $request) {
        $this->validate($request, [
            'stock_id' => 'required',
            'alasan_tolak' => 'required'
        ]);

        #loop seluruh stok yang dicentang
        foreach ($request->stock_id as $stock) {
            #ambil data user_id
            $user_id = auth()->user()->id;

            #update semua stok jadi reject
            $stock_revise = Stockrevise::find($stock);
            if ($stock_revise) {
                $stock_revise->is_rejected = 1;
                $stock_revise->reject_by = $user_id;
                $stock_revise->reason = $request->alasan_tolak;
                $stock_revise->reject_time = \Carbon\Carbon::now()->toDateTimeString();
                $stock_revise->save();

                #update stok product balik
                $product = Product::find($stock_revise->product_id);
                $product->qty -= $stock_revise->change_qty;

                #untuk data balance history
                $stock_in = 0;
                $stock_out = 0;
                if($stock_revise->change_qty < 0){
                    $stock_in = abs($stock_revise->change_qty);
                }else{
                    $stock_out = $stock_revise->change_qty;
                }
                StockBalanceFunction::addBalance($product->id, $stock_in, 0, $stock_out, "Revisi Stok ditolak. Stok balik. ID: " . $stock_revise->id);

                $product->save();
            }
        }

        return back()->with([
                    'msg' => 'Seluruh revisi stok sudah ditolak.'
        ]);
    }

    public function searchStockRevise(Request $request) {
        if (strlen($request['search']) <= 0) {
            return redirect('stockreviselist');
        }

        #Ambil seluruh list revisi
        $revise_list = Stockrevise::where('notes', 'like', '%' . $request->search . '%')
                ->orderBy('created_at', 'desc')
                ->get();

        return view('pages.admin-side.modules.stockrevise.stockreviselist')->with([
                    'revise_list' => $revise_list
        ]);

    }

}
