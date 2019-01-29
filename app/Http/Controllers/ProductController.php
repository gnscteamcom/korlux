<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Product;
use App\Brand;
use App\Category;
use App\Subcategory;
use App\Stockin;
use Illuminate\Http\Request;
use Excel;

class ProductController extends Controller {
    

    public function viewProduct($id){
    
        $products = Product::whereBrand_id($id)
                ->orderBy('product_name')->get();
        $brand = Brand::find($id);
        
        
        //menampilkan halaman daftar produk
        return view('pages.admin-side.modules.product.viewproduct')->with(array(
            'products' => $products,
            'brand_name' => $brand->brand,
            'brand_id' => $id
        ));
        
    }
    
    
    public function viewAddProduct(){
        
        $brands = Brand::orderBy('brand')->get();
        $categories = Category::orderBy('category')->get();
        
        return view('pages.admin-side.modules.product.addproduct')->with(array(
            'brands' => $brands,
            'categories' => $categories
        ));

    }
    
    
    public function editProduct($id){
        
        $product = Product::find($id);
        $brands = Brand::orderBy('brand')->get();
        $categories = Category::orderBy('category')->get();
        
        return view('pages.admin-side.modules.product.editproduct')->with(array(
            'product' => $product,
            'brands' => $brands,
            'categories' => $categories
        )); 
        
    }
    
    
    public function insertProduct(Request $request){
    
        $this->validate($request, [
            'brand' => 'required',
            'kategori' => 'required',
            'subkategori' => 'required',
            'barcode' => 'required|min:3|max:32',
            'kode_produk' => 'required|min:3|max:32',
            'nama_produk' => 'required|min:3',
            'berat' => 'required',
            'deskripsi' => 'required|min:10'
        ]);

        
        //validasi nama produk yang sama
        $product_name = Product::whereProduct_name($request['nama_produk'])->first();
        if($product_name){
            return back()->with('err', 'Nama produk sudah dipakai.. Silahkan gunakan nama produk yang lain..')->withInput();
        }

        
        //validasi nama produk yang sama
        $barcode = Product::whereBarcode($request['barcode'])->first();
        if($barcode){
            return back()->with('err', 'Barcode sudah terdaftar.. Silahkan gunakan barcode yang lain..')->withInput();
        }


        //Simpan produk baru
        $product = new Product;
        $product->brand_id = $request['brand'];
        $product->category_id = $request['kategori'];
        $product->subcategory_id = $request['subkategori'];
        $product->barcode = $request['barcode'];
        $product->product_code = $request['kode_produk'];
        $product->product_name = $request['nama_produk'];
        $product->product_desc = $request['deskripsi'];
        $product->weight = $request['berat'];
        $product->qty = 0;
        $product->save();


        return redirect('addproductimage')->with('msg', 'Produk baru berhasil ditambahkan..');
            
        
    }
    
    
    public function deleteProduct($id){
        
        //Hapus produk
        $product = Product::find($id);
        $brand = $product->brand_id;
        
        $stockins = Stockin::whereProduct_id($id)->get();
        foreach($stockins as $stockin){
            $stockin->delete();
        }

        $product->delete();
        
    
        //menampilkan halaman daftar produk
        return redirect('viewproduct/' . $brand)->with(array('msg' => 'Produk berhasil dihapus..'));
        
    }
    
    
    public function updateProduct(Request $request){
        
        $this->validate($request, [
            'barcode' => 'min:3|max:32',
            'product_code' => 'min:3|max:32',
            'product_name' => 'min:3',
            'desc' => 'min:10'
        ]);
        
        $product = Product::find($request['product_id']);

        $product_double = Product::whereBarcode($request['barcode'])
                ->where('id', '<>', $product->id)->first();
        if($product_double != null){
            return back()->with(array(
                'err' => 'Barcode tidak bisa digunakan karena sudah terdaftar di produk lain'
            ));
        }
        
        $product_double = Product::whereProduct_code($request['product_code'])
                ->where('id', '<>', $product->id)->first();
        if($product_double != null){
            return back()->with(array(
                'err' => 'Kode produk tidak bisa digunakan karena sudah terdaftar di produk lain'
            ));
        }
        
        //Update produk
        if(strlen($request['brand']) > 0 ){
            $product->brand_id = $request['brand'];
        }
        if(strlen($request['kategori']) > 0 ){
            $product->category_id = $request['kategori'];
        }
        if(strlen($request['subkategori']) > 0 ){
            $product->subcategory_id = $request['subkategori'];
        }
        if(strlen($request['barcode']) > 0 ){
            $product->barcode = $request['barcode'];
        }
        if(strlen($request['product_code']) > 0 ){
            $product->product_code = $request['product_code'];
        }
        if(strlen($request['product_name']) > 0 ){
            $product->product_name = $request['product_name'];
        }
        if(strlen($request['desc']) > 0 ){
            $product->product_desc = $request['desc'];
        }
        if(strlen($request['weight']) > 0 ){
            $product->weight = $request['weight'];
        }
        $product->save();

        return redirect('viewproduct/' . $product->brand_id)->with('msg', 'Data produk berhasil diubah..');
            
    }
    

    public function viewImportProduct(){
        
        //menampilkan halaman untuk melakukan import product
        return view('pages.admin-side.modules.product.importproduct');
        
    }
    
        
    public function downloadProductFormat(){
        
        return response()->download(storage_path() . '/importformat/product.xlsx');
        
    }

    
    public function importProduct(Request $request){
        
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

                if(ctype_space($row->barcode) || $row->barcode == null || $row->barcode == ''){
                    echo '<b style="color:red">Barcode Harus Diisi</b><br />';
                }
                else if(ctype_space($row->kode_produk) || $row->kode_produk == null || $row->kode_produk == ''){
                    echo '<b style="color:red">Kode Produk Harus Diisi</b><br />';
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
                else if(ctype_space($row->id_subkategori) || $row->id_subkategori == null || $row->id_subkategori == ''){
                    echo '<b style="color:red">ID Sub Kategori Harus Diisi</b><br />';
                }
                else{

                    //Cek apakah product sudah ada atau belum..
                    $product = Product::whereBarcode($row->barcode)->first();

                    //Insert kalau belum ada
                    if(!$product){
                        
                        #cek juga apakah kode produk ada yang sama
                        $product = Product::where('product_code', 'like', $row->kode_produk)
                                ->first();
                        if(!$product){
                            
                            $product = new Product;

                            $brand = Brand::whereInitial($row->inisial_merk)->first();

                            //kalau brand nya tidak ada, jangan lanjutin
                            if($brand != null){
                                $subcategory = Subcategory::find($row->id_subkategori);

                                //kalau subcategory nya tidak ada, buat category yang baru
                                if($subcategory != null){
                                    $product->brand_id = $brand->id;
                                    $product->category_id = $subcategory->category_id;
                                    $product->subcategory_id = $subcategory->id;

                                    $product->barcode = $row->barcode;
                                    $product->product_code = $row->kode_produk;
                                    $product->product_name = $row->nama_produk;
                                    $product->product_desc = $row->deskripsi;
                                    $product->qty = 0;
                                    $product->weight = $row->berat;
                                    $product->save();
                                }
                                else{
                                    echo '<b style="color:red">Produk TIDAK ditambahkan karena SUBKATEGORI tidak sesuai => ' . $row->nama_produk . '</b><br />';
                                }
                            }
                            else{
                                echo '<b style="color:red">Tidak ada Merk yang sesuai pada produk ' . $row->nama_produk . '</b><br />';
                            }
                        }
                        else{
                            echo '<b style="color:red">Kode Produk Ganda pada Produk ' . $row->nama_produk . '</b><br />';
                        }

                    }
                    else{
                        echo '<b style="color:red">Barcode Ganda pada Produk ' . $row->nama_produk . '</b><br />';
                    }
                    
                }

            });

        });
        
        echo '<br /><a href="' . url()->previous() . '">Kembali</a>';

    }
    
    
    public function searchProduct(Request $request){
    
        $products = Product::where('product_name', 'like', '%' . $request['search'] . '%')
                ->orderBy('product_name')->get();
        
        //menampilkan halaman daftar produk
        return view('pages.admin-side.modules.product.viewproduct')->with(array(
            'products' => $products,
            'brand_name' => 'Hasil Pencarian'
        ));
        
    }
    
}
