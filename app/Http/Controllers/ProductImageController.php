<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Product;
use App\Productimage;
use Illuminate\Http\Request;
use Image;

class ProductImageController extends Controller {
    

    public function viewProductImage(){
        
        $products = Product::distinct()
                ->join('productimages', 'productimages.product_id', '=', 'products.id')
                ->orderBy('products.product_name')
                ->select('products.id', 'products.product_code', 'products.barcode', 'products.product_name')
                ->paginate(50);
        
        return view('pages.admin-side.modules.productimage.viewproductimage')->with(array(
            'products' => $products
        ));
        
    }
    

    public function viewAddProductImage(){
        
        $products = Product::leftJoin('productimages', 'productimages.product_id', '=', 'products.id')
                ->whereNull('productimages.id')
                ->select('products.id', 'products.barcode', 'products.product_name')
                ->get();

        return view('pages.admin-side.modules.productimage.addproductimage')->with(array(
            'products' => $products
        ));
        
    }
    
    
    public function viewEditProductImage($id){
        
        $product = Product::find($id);
        
        return view('pages.admin-side.modules.productimage.editproductimage')->with(array(
            'product' => $product
        ));
        
    }
    
    
    public function deleteProductImage($id){
        
        $product = Product::find($id);
        
        $productimages = $product->productimages;
        foreach($productimages as $productimage){
            if(file_exists($productimage->image_path)){
                unlink($productimage->image_path);
            }
            $productimage->delete();
        }
        
        return redirect('viewproductimage')->with(array(
            'msg' => 'Berhasil menghapus foto produk'
        ));
        
    }
    
    
    public function deleteOneProductImage($id){
        
        $productimage = Productimage::find($id);
        
        if(file_exists($productimage->image_path)){
            unlink($productimage->image_path);
        }
        $productimage->delete();
        
        return back()->with(array(
            'err' => 'Berhasil menghapus foto produk'
        ));
        
    }
    
    
    public function uploadProductImage(Request $request){
        
        $this->validate($request, [
            'produk' => 'required',
            'foto1' => 'image',
            'foto2' => 'image',
            'foto3' => 'image',
        ]);
        
        $foto1 = $request->file('foto1');
        $foto2 = $request->file('foto2');
        $foto3 = $request->file('foto3');
        
        $file_name = date('ymdhis');
        
        if($request->hasFile('foto1')){
            
            if($this->validateImage($foto1)){
                return redirect('addproductimage')->with(array(
                    'err' => 'Ukuran foto 1 harus di bawah 200KB'
                ));
            }
            else{
                $foto1->move('storage/upload/productimages/', $file_name . '1.' . $foto1->getClientOriginalExtension());
            }
            
            $this->resizeImage($file_name, $foto1, '1');
            
            $productimage = new Productimage;
            $productimage->product_id = $request['produk'];
            $productimage->image_path = 'storage/upload/productimages/' . $file_name . '1.' . $foto1->getClientOriginalExtension();
            $productimage->save();
            
        }
        if($request->hasFile('foto2')){
            
            if($this->validateImage($foto2)){
                return redirect('addproductimage')->with(array(
                    'err' => 'Ukuran foto 2 harus di bawah 200KB'
                ));
            }
            else{
                $foto2->move('storage/upload/productimages/', $file_name . '2.' . $foto2->getClientOriginalExtension());
            }
            
            $this->resizeImage($file_name, $foto2, '2');
                        
            $productimage = new Productimage;
            $productimage->product_id = $request['produk'];
            $productimage->image_path = 'storage/upload/productimages/' . $file_name . '2.' . $foto2->getClientOriginalExtension();
            $productimage->save();

            
        }
        if($request->hasFile('foto3')){
            
            if($this->validateImage($foto3)){
                return redirect('addproductimage')->with(array(
                    'err' => 'Ukuran foto 3 harus di bawah 200KB'
                ));
            }
            else{
                $foto3->move('storage/upload/productimages/', $file_name . '3.' . $foto3->getClientOriginalExtension());
            }
            
            $this->resizeImage($file_name, $foto3, '3');
                        
            $productimage = new Productimage;
            $productimage->product_id = $request['produk'];
            $productimage->image_path = 'storage/upload/productimages/' . $file_name . '3.' . $foto3->getClientOriginalExtension();
            $productimage->save();
            
        }
        
        return redirect('addproductimage')->with(array(
            'msg' => 'Berhasil menambah foto produk'
        ));
        
    }
    
    
    public function updateProductImage(Request $request){
        
        $this->validate($request, [
            'produk' => 'required',
            'foto1' => 'image',
            'foto2' => 'image',
            'foto3' => 'image',
        ]);
        
        $foto1 = $request->file('foto1');
        $foto2 = $request->file('foto2');
        $foto3 = $request->file('foto3');
        
        $file_name = date('ymdhis');
        
        if($request->hasFile('foto1')){
            
            if($this->validateImage($foto1)){
                return redirect('addproductimage')->with(array(
                    'err' => 'Ukuran foto 1 harus di bawah 200KB dengan file .jpg .png .gif'
                ));
            }
            else{
                $foto1->move('storage/upload/productimages/', $file_name . '1.' . $foto1->getClientOriginalExtension());
            }
            
            $this->resizeImage($file_name, $foto1, '1');
            
            $productimage = new Productimage;
            $productimage->product_id = $request['produk'];
            $productimage->image_path = 'storage/upload/productimages/' . $file_name . '1.' . $foto1->getClientOriginalExtension();
            $productimage->save();
            
        }
        if($request->hasFile('foto2')){
            
            if($this->validateImage($foto2)){
                return redirect('addproductimage')->with(array(
                    'err' => 'Ukuran foto 2 harus di bawah 200KB dengan file .jpg .png .gif'
                ));
            }
            else{
                $foto2->move('storage/upload/productimages/', $file_name . '2.' . $foto2->getClientOriginalExtension());
            }
            
            $this->resizeImage($file_name, $foto2, '2');
            
            $productimage = new Productimage;
            $productimage->product_id = $request['produk'];
            $productimage->image_path = 'storage/upload/productimages/' . $file_name . '2.' . $foto2->getClientOriginalExtension();
            $productimage->save();

            
        }
        if($request->hasFile('foto3')){
            
            if($this->validateImage($foto3)){
                return redirect('addproductimage')->with(array(
                    'err' => 'Ukuran foto 3 harus di bawah 200KB dengan file .jpg .png .gif'
                ));
            }
            else{
                $foto3->move('storage/upload/productimages/', $file_name . '3.' . $foto3->getClientOriginalExtension());
            }
            
            $this->resizeImage($file_name, $foto3, '3');
                        
            $productimage = new Productimage;
            $productimage->product_id = $request['produk'];
            $productimage->image_path = 'storage/upload/productimages/' . $file_name . '3.' . $foto3->getClientOriginalExtension();
            $productimage->save();
            
        }
        
        return redirect('viewproductimage')->with(array(
            'msg' => 'Berhasil menambah foto produk'
        ));
        
    }
    
    
    private function validateImage($foto){

        $size = $foto->getClientSize();

        //max file size adalah 200 KB
        if($size > 204800){
            return true;
        }
        
        $ext = $foto->getClientOriginalExtension();
        
        if($ext != "jpg" && $ext != "gif" && $ext != "png" ){
            return true;
        }
        
        return false;
        
    }
    
    
    private function resizeImage($file_name, $file, $number){
            
        $image = Image::make('storage/upload/productimages/' . $file_name . $number . '.' . $file->getClientOriginalExtension())->resize(500,500);
        $image->save('storage/upload/productimages/' . $file_name . $number . '.' . $file->getClientOriginalExtension());
        
    }
    
    
    public function searchProductImage(Request $request){
    
        $products = Product::distinct()
                ->where('product_name', 'like', '%' . $request['search'] . '%')
                ->join('productimages', 'productimages.product_id', '=', 'products.id')
                ->orderBy('products.product_name')
                ->select('products.id', 'products.product_code', 'products.barcode', 'products.product_name');
        
        $counter = $products->count();
        $products = $products->paginate($counter);
        
        return view('pages.admin-side.modules.productimage.viewproductimage')->with(array(
            'products' => $products
        ));
        
    }
    
    
}
