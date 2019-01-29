<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;
use App\Subcategory;
use App\Product;

class SubCategoryController extends Controller {

    
    public function viewSubCategory(){
    
        $subcategories = Subcategory::orderBy('position')->get();
        
        return view('pages.admin-side.modules.subcategory.viewsubcategory')->with('subcategories', $subcategories);
        
    }


    public function viewAddSubCategory(){
        
        $categories = Category::orderBy('category')->get();
        
        return view('pages.admin-side.modules.subcategory.addsubcategory')->with(array(
            'categories' => $categories
        ));
        
    }
    
    
    public function editSubCategory($id){
        
        $categories = Category::orderBy('category')->get();
        $subcategory = Subcategory::find($id);
        
        return view('pages.admin-side.modules.subcategory.editsubcategory')->with(array(
            'categories' => $categories,
            'subcategory' => $subcategory
        ));
        
    }
    
    
    public function insertSubCategory(Request $request){
    
        $this->validate($request, [
            'kategori' => 'required',
            'subkategori' => 'required|min:3|max:32',
            'posisi' => 'required|min:1'
        ]);
        
        $category_id = explode(" | ", $request['kategori'])[0];
            
        //Validasi subkategori yang sudah ada
        $subcategory = Subcategory::where('category_id', '=', $category_id)
                ->whereSubcategory($request['subkategori'])->first();

        if($subcategory != null){
            return back()->with('err', 'Subkategori sudah terdaftar..')->withInput();
        }
        
        $position = Subcategory::wherePosition($request['posisi'])->first();
        
        if($position != null){
            return back()->with(array(
                'err' => 'Posisi sudah terdaftar, silahkan gunakan posisi lain'
            ))->withInput();
        }

        //Simpan subkategori baru
        $subcategory = new Subcategory;
        $subcategory->category_id = $category_id;
        $subcategory->subcategory = $request['subkategori'];
        $subcategory->position = $request['posisi'];
        $subcategory->save();

        return redirect('viewsubcategory')->with('msg', 'Berhasil menambah subkategori baru');            
        
    }
    
    
    public function deleteSubCategory($id){
        
        //Hapus subkategori
        $subcategory = Subcategory::find($id);
        
        //Hapus produk dengan kategori yang akan dihapus
        $products = Product::whereSubcategory_id($id)->get();
        
        foreach($products as $product){
            $product->delete();
        }
        
        $subcategory->delete();
        
        return redirect('viewsubcategory')->with(array(
            'msg' => 'Berhasil menghapus subkategori'
        ));
    }
    
    
    public function updateSubCategory(Request $request){
        
        $this->validate($request, [
            'kategori' => 'required',
            'subkategori' => 'required|min:3|max:32',
            'posisi' => 'required|min:1'
        ]);
        
        $category_id = explode(" | ", $request['kategori'])[0];
            
        //Validasi subkategori yang sudah ada
        $subcategory = Subcategory::where('id', '<>', $request['subcategory_id'])
                ->where('category_id', '=', $category_id)
                ->whereSubcategory($request['subkategori'])->first();

        if($subcategory != null){
            return back()->with('err', 'Subkategori sudah terdaftar..')->withInput();
        }
        
        $subcategory = Subcategory::where('id', '<>', $request['subcategory_id'])
                ->wherePosition($request['posisi'])->first();
        
        if($subcategory != null){
            return back()->with(array(
                'err' => 'Posisi sudah terdaftar, silahkan gunakan posisi lain'
            ))->withInput();
        }


        //Simpan subkategori
        $subcategory = Subcategory::find($request['subcategory_id']);
        $subcategory->category_id = $category_id;
        $subcategory->subcategory = $request['subkategori'];
        $subcategory->position = $request['posisi'];
        $subcategory->save();



        return redirect('viewsubcategory')->with('msg', 'Berhasil memperbarui kategori..');
            
        
    }
    
}
