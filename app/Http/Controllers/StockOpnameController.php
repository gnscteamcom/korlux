<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use App\Http\Controllers\Custom\StockFunction;
use Excel;

class StockOpnameController extends Controller {

    
    public function viewStockOpname(){
    
        return view('pages.admin-side.modules.stockopname.viewstockopname');
        
    }
    

    public function viewStockCorrection(){
        
        //hanya produk yang bukan paket yang bisa dikoreksi
        $products = Product::where('is_set', '=', 0)
                ->orderBy('barcode')->get();
        
        return view('pages.admin-side.modules.stockopname.viewstockcorrection')->with(array(
            'products' => $products
        ));
        
    }
    
    
    public function viewStockTotal(){
        
        $products = Product::orderBy('barcode')->paginate(50);
        
        return view('pages.admin-side.modules.stockopname.viewstocktotal')->with(array(
            'products' => $products
        ));
        
    }
    
    public function downloadStockTotal(){
        Custom\ExportFunction::exportForStockTotal();
    }
    
    
    public function updateStockCorrection(Request $request){
        
        $this->validate($request, [
            'barcode' => 'required',
            'stok' => 'required',
            'stok_cadangan' => 'required'
        ]);
        
        
        $product = Product::whereBarcode($request['barcode'])->first();
        
        if($product == null){
            return back()->with(array(
               'err' => 'Barcode tidak terdaftar' 
            ));
        }
        
        #update stock cadangan
        $product->reserved_qty = $request->stok_cadangan;
        $product->save();
        
        $current_booked_item = StockFunction::getStockBooked($product->id);
        
        if($product->qty + $current_booked_item != $request['stok']){
            
            if(Custom\CapitalFunction::stockCorrection($product, $request['stok'])){
                return back()->with('err', 'Gagal, Stok perubahan akan minus..');
            }
            
        }
        
        return back()->with('msg', 'Berhasil mengoreksi stok');
        
    }
    
        
    public function downloadStockOpnameFormat(){
        
        Custom\ExportFunction::exportForImportStockOpname();
        
    }

    
    public function importStockOpname(Request $request){
        
        $this->validate($request, [
            'file' => 'required'
        ]);
            
        //inisialisasi data
        $file = $request['file'];
        $filesize = $file->getSize(); //hasil dalam satuan bytes..

        //Validasi ukuran file
        //Ukuran file > 0B
        if($filesize <= 0){
            return back()->with('err', 'File tidak ada data..');
        }

        //Validasi extension file
        //Extension file harus .xls atau .xlsx
        if($file->getClientOriginalExtension() != 'xls' && $file->getClientOriginalExtension() != 'xlsx'  ){
            return back()->with('err', 'File harus .xls atau .xlss');
        }


        echo '<h1>Daftar Import yang Gagal :</h1>';

        Excel::selectSheets('Sheet1')->load($file, function($reader){

            //Baca smua sheets
            $reader->each(function($row){

                if(ctype_space($row->id) || $row->id == null || $row->id == ''){
                    echo '<b style="color:red">ID jangan dikosongkan</b><br />';
                }
                else if(ctype_space($row->barcode) || $row->barcode == null || $row->barcode == ''){
                    echo '<b style="color:red">Barcode Harus Diisi pada produk dengan ID ' . $row->id . '. TIDAK DIPROSES</b><br />';
                }
                else if(ctype_digit($row->qty)){
                    echo '<b style="color:red">Qty Tidak Diisi pada produk dengan ID ' . $row->id . '. TIDAK DIPROSES</b><br />';
                }
                else if(ctype_digit($row->reserved_qty)){
                    echo '<b style="color:red">Reserved Qty Tidak Diisi pada produk dengan ID ' . $row->id . '. TIDAK DIPROSES</b><br />';
                }
                else{
                    
                    //Cek apakah barcode salah
                    $product = Product::find($row->id);

                    //Kalau tidak ada produknya, abaikan..
                    if($product == null){
                        echo '<b style="color:red">Produk dengan ID : ' . $row->id . ' tidak sinkron dengan nama produk dan barcodenya. TIDAK DIPROSES</b><br />';
                    }
                    else{
                        #update stok cadangan
                        $product->reserved_qty = $row->reserved_qty;
                        $product->save();
                        
                        if(Custom\CapitalFunction::stockCorrection($product, $row->qty)){
                            echo '<b style="color:red">Barcode ' . $row->barcode . ' stok nya akan minus..</b><br />';
                        }
                        else{
                            echo '<b style="color:orange">Produk dengan ID : ' . $row->id . ' SELESAI DIPROSES</b><br />';
                        }
                    }
                    
                }

            });

        });
        
        echo '<br /><a href="' . url()->previous() . '">Kembali</a>';

    }
    
    
    public function searchStockByProductName(Request $request){
    
        $products = Product::where('product_name', 'like', '%' . $request['search'] . '%')
                ->orderBy('product_name')->paginate(50);
        
        //menampilkan halaman daftar produk
        return view('pages.admin-side.modules.stockopname.viewstocktotal')->with(array(
            'products' => $products,
            'search' => $request['search']
        ));
        
    }
    
    
}
