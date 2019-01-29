<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Brand;
use App\Product;

class BrandController extends Controller {

    
    public function viewBrand(){
    
        $brands = Brand::orderBy('brand')->get();
        
        //menampilkan halaman daftar kategori
        return view('pages.admin-side.modules.brand.viewbrand')->with('brands', $brands);
        
    }
    

    public function viewAddBrand(){
        
        //menampilkan halaman untuk menambahkan kategori
        return view('pages.admin-side.modules.brand.addbrand');
        
    }
    
    
    public function editBrand($id){
        
        $brand = Brand::find($id);
        return view('pages.admin-side.modules.brand.editbrand')->with('brand', $brand);
        
    }
    
    
    public function insertBrand(Request $request){
    
        $this->validate($request, [
            'merk' => 'required|min:3|max:32',
            'inisial' => 'required|min:1|max:6'
        ]);
        
            
        //Validasi kategori yang sudah ada
        $brand = Brand::whereBrand($request['merk'])->first();

        if($brand != null){
            return back()->with('err', 'Mohon gunakan merk lain..')->withInput();
        }


        //Simpan kategori baru
        $brand = new Brand;
        $brand->brand = $request['merk'];
        $brand->initial = $request['inisial'];
        $brand->save();

        return redirect('viewbrand')->with('msg', 'Berhasil menambah merk baru');            
    }
    
    
    public function deleteBrand($id){
        
        //Hapus kategori
        $brand = Brand::find($id);
        
        //Hapus produk dengan kategori yang akan dihapus
        $products = Product::whereBrand_id($id)->get();
        
        foreach($products as $product){
            $product->delete();
        }
        
        $brand->delete();
        
        return redirect('viewbrand');
    }
    
    
    public function updateBrand(Request $request){
        
        $this->validate($request, [
            'merk' => 'required|min:3|max:32',
            'inisial' => 'required|min:1|max:6'
        ]);
        

        $brand = Brand::find($request['brand_id']);

        //Update merk
        $brand->brand = $request['merk'];
        $brand->initial = $request['inisial'];
        $brand->save();

        return redirect('viewbrand')->with('msg', 'Berhasil memperbarui merk..');
            
        
    }
    
}
