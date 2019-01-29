<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;
use App\Product;

class CategoryController extends Controller {

    
    public function viewCategory(){
    
        $categories = Category::orderBy('position')->get();
        
        return view('pages.admin-side.modules.category.viewcategory')->with('categories', $categories);
        
    }
    

    public function viewAddCategory(){
        
        return view('pages.admin-side.modules.category.addcategory');
        
    }
    
    
    public function editCategory($id){
        
        $category = Category::find($id);
        return view('pages.admin-side.modules.category.editcategory')->with('category', $category);
        
    }
    
    
    public function insertCategory(Request $request){
    
        $this->validate($request, [
            'kategori' => 'required|min:3|max:32',
            'posisi' => 'required|min:1'
        ]);
        
            
        //Validasi kategori yang sudah ada
        $kategori = Category::whereCategory($request['kategori'])->first();

        if($kategori != null){
            return back()->with('err', 'Mohon gunakan kategori lain..')->withInput();
        }
        
        $position = Category::wherePosition($request['posisi'])->first();
        
        if($position != null){
            return back()->with(array(
                'err' => 'Silahkan gunakan posisi yang lain'
            ));
        }


        //Simpan kategori baru
        $category = new Category;
        $category->category = $request['kategori'];
        $category->position = $request['posisi'];
        $category->save();

        return redirect('viewcategory')->with('msg', 'Berhasil menambah kategori baru');            
    }
    
    
    public function deleteCategory($id){
        
        $product = Product::join('categories', 'categories.id', '=', 'products.category_id')
                ->where('products.qty', '>', 0)
                ->where('categories.id', '=', $id)
                ->first();
        
        if($product){
            return redirect('viewcategory')->with(array(
                'msg' => 'Tidak bisa menghapus kategori karena ada produk yang masih ada stok'
            ));
        }
        
        //Hapus kategori
        $category = Category::find($id);
        
        //Hapus produk dengan kategori yang akan dihapus
        $products = Product::whereCategory_id($id)->get();
        
        foreach($products as $product){
            $product->delete();
        }
        
        $category->delete();
        
        return redirect('viewcategory')->with(array(
            'msg' => 'Kategori berhasil dihapus. Seluruh produk telah terhapus karena stok sudah 0.'
        ));
    }
    
    
    public function updateCategory(Request $request){
        
        $this->validate($request, [
            'kategori' => 'required|min:3|max:32',
            'posisi' => 'required|min:1'
        ]);
        

        $category = Category::find($request['category_id']);
        
        $position = Category::wherePosition($request['posisi'])
                ->where('id', '<>', $category->id)->first();
        
        if($position != null){
            return back()->with(array(
                'err' => 'Silahkan gunakan posisi yang lain'
            ));
        }

        //Update kategori
        $category->category = $request['kategori'];
        $category->position = $request['posisi'];
        $category->save();

        return redirect('viewcategory')->with('msg', 'Berhasil memperbarui kategori..');
            
        
    }
    
}
