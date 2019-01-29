<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Custom\StockFunction;
use App\Http\Controllers\Custom\StockBalanceFunction;
use Excel;
use App\Stockin;
use App\Product;

class StockinController extends Controller {

    public function viewStockin() {

        $stock_ins = Stockin::orderBy('created_at', 'desc')->paginate(50);

        //menampilkan halaman daftar stok
        return view('pages.admin-side.modules.stockin.viewstockin')->with(array(
                    'stock_ins' => $stock_ins
        ));
    }

    public function viewImportStockin() {

        //menampilkan halaman untuk menambahkan stok
        return view('pages.admin-side.modules.stockin.viewimportstockin');
    }

    public function downloadStockinFormat() {

        Custom\ExportFunction::exportForImportStockin();
    }

    public function importStockin(Request $request) {

        $this->validate($request, [
            'file' => 'required'
        ]);


        //inisialisasi data
        $file = $request['file'];
        $filesize = $file->getSize(); //hasil dalam satuan bytes..
        //Validasi ukuran file
        //Ukuran file > 0B
        if ($filesize <= 0) {
            return back()->with('err', 'File tidak ada data');
        }

        //Validasi extension file
        //Extension file harus .xls atau .xlsx
        if ($file->getClientOriginalExtension() != 'xls' && $file->getClientOriginalExtension() != 'xlsx') {
            return back()->with('err', 'File harus .xls atau .xlss');
        }

        echo '<h1>Daftar Import yang Gagal :</h1>';

        Excel::selectSheets('Sheet1')->load($file, function($reader) {

            //Baca smua sheets
            $reader->each(function($row) {

                if (ctype_space($row->id) || $row->id == null || $row->id == '') {
                    echo '<b style="color:red">ID jangan dihapus</b><br />';
                } else if (ctype_space($row->barcode) || $row->barcode == null || $row->barcode == '') {
                    echo '<b style="color:red">Barcode Harus Diisi</b><br />';
                } else if (!is_numeric($row->qty)) {
                    echo '<b style="color:red">Qty Harus Angka</b><br />';
                } else if (!is_numeric($row->reserved_qty)) {
                    echo '<b style="color:red">Reserved Qty Harus Angka</b><br />';
                } else {

                    if ($row->qty > 0 || $row->reserved_qty > 0) {

                        //cek produk dulu apakah benar semua
                        $check_product = Product::find($row->id);

                        if ($check_product) {

                            $product = Product::withTrashed()->whereId($row->id)->first();

                            if ($product) {
                                //simpan stockin
                                $stockin = new Stockin;
                                $stockin->product_id = $product->id;
                                $stockin->qty = $row->qty;
                                $stockin->remaining_qty = $row->qty;
                                $stockin->reserved_qty = $row->reserved_qty;
                                $stockin->save();

                                //untuk balikin produk yang udah terhapus, dibuat available
                                if ($product->deleted_at != null) {
                                    $product->deleted_at = null;
                                }

                                #simpan ke history
                                StockBalanceFunction::addBalance($product->id, $stockin->qty, 0, 0, "Stok Masuk");
        
                                //simpan stock nya di produk
                                $product->qty += $stockin->qty;
                                $product->reserved_qty += $stockin->reserved_qty;
                                $product->last_stock_update = \Carbon\Carbon::now()->toDateString();
                                $product->save();
                            } else {
                                echo '<b style="color:red">Tidak ada Produk pada Barcode ' . $row->barcode . '</b><br />';
                            }
                        } else {
                            echo '<b style="color:red">Tidak ada Produk pada Barcode ' . $row->barcode . ' dengan ID : ' . $row->id . ' dengan nama : ' . $row->nama_produk . '</b><br />';
                        }
                    }
                }
            });
        });

        echo '<br /><a href="' . url()->to('viewstockin') . '">Kembali</a>';
    }

    public function editStockin($id) {

        $stockin = Stockin::find($id);

        return view('pages.admin-side.modules.stockin.editstockin')->with(array('stockin' => $stockin));
    }

    public function updateStockin(Request $request) {

        $this->validate($request, [
            'qty' => 'required|min:1'
        ]);


        $stockin = Stockin::find($request['stock_in_id']);


        //update stok produk
        $product = Product::find($stockin->product_id);
        $new_qty = $product->qty - $stockin->qty + $request['qty'];

        //update total qty
        $product->qty = $new_qty;
        $product->last_stock_update = \Carbon\Carbon::now()->toDateString();
        $product->save();

        //Update stock in
        $stockin->qty = $request['qty'];
        $stockin->remaining_qty = $request['qty'];
        $stockin->save();

        return redirect('viewstockin')->with('msg', 'Data stok masuk berhasil diubah..');
    }

    public function searchStockin(Request $request) {

        $stock_ins = Stockin::join('products', 'products.id', '=', 'stockins.product_id')
                ->where('products.product_name', 'like', '%' . $request['search'] . '%')
                ->orderBy('stockins.created_at', 'desc');

        $counter = $stock_ins->count();

        $stock_ins = $stock_ins
                ->select('stockins.id', 'stockins.qty', 'stockins.remaining_qty', 'stockins.product_id', 'stockins.created_at')
                ->paginate($counter);

        //menampilkan halaman daftar stok
        return view('pages.admin-side.modules.stockin.viewstockin')->with(array(
                    'stock_ins' => $stock_ins
        ));
    }

    public function getStockDesc(Request $request) {
        $product_id = $request->product_id;
        $product = Product::find($product_id);
        if (!$product) {
            $response = [
                'result' => 0,
                'data' => []
            ];
        }
        
        $stock_book = StockFunction::getStockBooked($product_id);
        $stock_total = $product->qty + $product->reserved_qty + $stock_book;
        
        $response = [
            'result' => 1,
            'data' => 'Stok Total: ' . $stock_total . ', Stok Cadangan: ' . $product->reserved_qty
        ];
        
        return json_encode($response);
    }
    
    public function viewStockBalance() {
        $products = Product::orderBy('product_name')
                        ->select('id', 'product_name')->get();
        return view('pages.admin-side.modules.stockin.stockbalance')->with([
                    'products' => $products
        ]);
    }

    public function downloadStockBalance(Request $request) {
        Custom\ExportFunction::exportForStockBalance($request->product, $request->date_start, $request->date_end);
    }

}
