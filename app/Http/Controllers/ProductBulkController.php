<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use App\Brand;
use App\Subcategory;
use Excel;

class ProductBulkController extends Controller {
    
    
    public function viewBulkUpdateProduct(Request $request){
        
        $product_id = $request['product_id'];
        
        if(sizeof($product_id) == 0){
            return back()->with(array(
                'msg' => 'Anda tidak memilih satupun produk untuk diubah'
            ));
        }
        
        $product_id = http_build_query($product_id);
        
        return view('pages.admin-side.modules.product.editbulkproduct')->with(array(
            'product_id' => $product_id
        ));
        
    }
    
    
    public function downloadBulkProductFormat($product_id = null){
        
        parse_str($product_id, $array_in);
        
        if(sizeof($product_id) > 0){
            Custom\ExportFunction::exportForUpdateProductBulk($array_in);
        }
        
        return back();
        
    }
    
    
    public function bulkDeleteProduct(Request $request){
        
        foreach($request['product_id'] as $product_id){
            $product = Product::find($product_id);
            if($product){
                if($product->qty == 0){
                    $product->delete();
                }
            }
        }
        
        return back()->with(array('msg' => 'Produk yang dipilih telah berhasil dihapus.. Produk dengan stok lebih dari 0 tidak dihapus..'));

    }
    
    
    public function bulkUpdateProduct(Request $request){
        
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
                    echo '<b style="color:red">Ada ID yang kosong pada produk : ' . $row->nama_produk . '</b><br />';
                }
                else if(ctype_space($row->barcode) || $row->barcode == null || $row->barcode == ''){
                    echo '<b style="color:red">Barcode pada produk ' . $row->nama_produk . ' kosong.</b><br />';
                }
                else if(ctype_space($row->kode_produk) || $row->kode_produk == null || $row->kode_produk == ''){
                    echo '<b style="color:red">Kode Produk pada produk ' . $row->nama_produk . ' kosong.</b><br />';
                }
                else if(ctype_space($row->nama_produk) || $row->nama_produk == null || $row->nama_produk == ''){
                    echo '<b style="color:red">Nama Produk Harus Diisi</b><br />';
                }
                else if(!is_numeric($row->berat)){
                    echo '<b style="color:red">Berat Harus Angka</b><br />';
                }
                else if(ctype_space($row->berat) || $row->berat == null || $row->berat == '' || $row->berat == 0){
                    echo '<b style="color:red">Berat Harus Diisi</b><br />';
                }
                else if(ctype_space($row->inisial_merk) || $row->inisial_merk == null || $row->inisial_merk == ''){
                    echo '<b style="color:red">Inisial Merk Harus Diisi</b><br />';
                }
                else if(ctype_space($row->subcategory_id) || $row->subcategory_id == null || $row->subcategory_id == ''){
                    echo '<b style="color:red">ID Sub Kategori Harus Diisi</b><br />';
                }
                else{

                    //Ambil produk
                    $product = Product::find($row->id);

                    //Kalau ada update, kalau tidak, skip
                    if($product){

                        $brand = Brand::whereInitial($row->inisial_merk)->first();

                        //kalau brand nya tidak ada, jangan lanjutin
                        if($brand != null){
                            $subcategory = Subcategory::find($row->subcategory_id);

                            //kalau subcategory nya tidak ada, buat category yang baru
                            if($subcategory != null){
                                
                                $product_2 = Product::where('product_code', 'like', $row->kode_produk)
                                        ->where('id', '!=', $row->id)
                                        ->first();
                                if($product_2){
                                    echo '<b style="color:red">Kode produk Ganda pada produk dengan ID : ' . $row->id . '</b><br />';
                                }
                                else{
                                    $product->brand_id = $brand->id;
                                    $product->category_id = $subcategory->category_id;
                                    $product->subcategory_id = $subcategory->id;

                                    $product->barcode = $row->barcode;
                                    $product->product_code = $row->kode_produk;
                                    $product->product_name = $row->nama_produk;
                                    $product->product_desc = $row->deskripsi;
                                    $product->weight = $row->berat;
                                    $product->save();

                                    echo '<b style="color:orange">Produk dengan ID : ' . $row->id . ' BERHASIL DIUBAH</b><br />';
                                }
                                
                            }
                            else{
                                echo '<b style="color:red">Produk TIDAK diubah karena ID SUBKATEGORI tidak sesuai pada produk dengan ID : ' . $row->id . '</b><br />';
                            }
                        }
                        else{
                            echo '<b style="color:red">Tidak ada Merk dengan inisial ' . $row->inisial_merk . ' pada produk dengan ID : ' . $row->id . '</b><br />';
                        }

                    }
                    else{
                        echo '<b style="color:red">Tidak ada produk dengan ID : ' . $row->id . '</b><br />';
                    }
                    
                }

            });

        });
        
        echo '<br /><a href="' . url()->to('bulkproductfinish') . '">Kembali</a>';

    }
    
    
    public function bulkProductFinish(){
        
        return view('pages.admin-side.modules.product.editbulkproduct')->with(array(
            'product_id' => '',
            'msg' => 'Selesai memproses perubahan data..'
        ));
        
    }
    
    
    
}
