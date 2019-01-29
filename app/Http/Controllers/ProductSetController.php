<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Productset;
use App\Product;
use App\Brand;
use App\Category;
use App\Stockin;

class ProductSetController extends Controller {

    public function viewProductSets() {
        $products = Product::where('is_set', '=', 1)->orderBy('product_name')->get();
        return view('pages.admin-side.modules.productset.viewproductset')->with([
                    'products' => $products
        ]);
    }

    public function searchProductSet(Request $request) {
        $products = Product::where('is_set', '=', 1);
        if (strlen($request->search) > 0) {
            $products = $products->where('product_name', 'like', '%' . $request['search'] . '%');
        }
        $products = $products->orderBy('product_name')->get();

        //menampilkan halaman daftar produk
        return view('pages.admin-side.modules.productset.viewproductset')->with(array(
                    'products' => $products
        ));
    }

    public function addProductSet() {
        $products = Product::where('is_set', '=', 0)->get();
        $brands = Brand::orderBy('brand')->get();
        $categories = Category::orderBy('category')->get();
        return view('pages.admin-side.modules.productset.addproductset')->with([
                    'products' => $products,
                    'brands' => $brands,
                    'categories' => $categories
        ]);
    }

    public function editProductSet($id) {
        $products = Product::where('is_set', '=', 0)->get();
        $current_products = Productset::join('products', 'products.id', '=', 'productsets.product_id')
                ->leftJoin('prices', 'prices.id', '=', 'products.currentprice_id')
                ->where('productsets.set_id', '=', $id)
                ->select('products.id', 'products.barcode', 'products.product_name',
                        'prices.regular_price', 'products.qty')
                ->get();
        $product = Product::find($id);
        $brands = Brand::orderBy('brand')->get();
        $categories = Category::orderBy('category')->get();
        return view('pages.admin-side.modules.productset.editproductset')->with([
                    'products' => $products,
                    'current_products' => $current_products,
                    'product' => $product,
                    'brands' => $brands,
                    'categories' => $categories
        ]);
    }

    public function insertProductSet(Request $request) {

        $this->validate($request, [
            'brand' => 'required',
            'kategori' => 'required',
            'subkategori' => 'required',
            'barcode' => 'required|min:3|max:32',
            'kode_produk' => 'required|min:3|max:32',
            'nama_produk' => 'required|min:3',
            'berat' => 'required',
            'deskripsi' => 'required|min:10',
            'product_id' => 'required'
        ]);

        //validasi nama produk yang sama
        $product_name = Product::whereProduct_name($request['nama_produk'])->first();
        if ($product_name) {
            return back()->with('err', 'Nama produk sudah dipakai.. Silahkan gunakan nama produk yang lain..')->withInput();
        }

        //validasi nama produk yang sama
        $barcode = Product::whereBarcode($request['barcode'])->first();
        if ($barcode) {
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
        $product->is_set = 1;
        $product->save();

        //insert ke product set
        $set_qty = 0;
        $i = 1;
        //set qty dari product set dengan qty terkecil dari anaknya
        foreach ($request->product_id as $product_id) {
            $product_qty = Product::find($product_id);
            if($i == 1) $set_qty = $product_qty->qty;
            $productset = new Productset;
            $productset->set_id = $product->id;
            $productset->product_id = $product_id;
            $productset->save();
            
            if($set_qty > $product_qty->qty){
                $set_qty = $product_qty->qty;
            }
            $i++;
        }
        $product->qty = $set_qty;
        $product->save();

        return redirect('addproductimage')->with('msg', 'Produk set baru berhasil ditambahkan..');
    }

    public function addProduct(Request $request) {
        if (strlen($request['product']) < 1) {
            return [
                'err' => 'No Data'
            ];
        }

        $product_id = $request->product;
        $product = Product::where('id', '=', $product_id)
                ->where('is_set', '=', 0)
                ->first();
        if (!$product) {
            return [
                'err' => 'No Data'
            ];
        }
        
        $price = 0;
        if($product->currentprice_id > 0){
            $price = $product->currentprice->regular_price;
        }

        $data = [
            'product_id' => $product->id,
            'product_name' => $product->product_name,
            'barcode' => $product->barcode,
            'qty' => $product->qty,
            'price' => 'Rp. ' . number_format($price, 2, ',', '.')
        ];

        return $data;
    }
    
    public function deleteProductSet($id) {
        //Hapus produk
        $product = Product::find($id);

        $stockins = Stockin::whereProduct_id($id)->get();
        foreach ($stockins as $stockin) {
            $stockin->delete();
        }

        $product->delete();
        //menampilkan halaman daftar produk
        return redirect('productsets')->with(array('msg' => 'Produk paket berhasil dihapus..'));
    }

    public function updateProductSet(Request $request) {

        $this->validate($request, [
            'barcode' => 'min:3|max:32',
            'product_code' => 'min:3|max:32',
            'product_name' => 'min:3',
            'desc' => 'min:10',
            'product_id' => 'required'
        ]);

        $product = Product::find($request['productset_id']);

        $product_double = Product::whereBarcode($request['barcode'])
                        ->where('id', '<>', $product->id)->first();
        if ($product_double != null) {
            return back()->with(array(
                        'err' => 'Barcode tidak bisa digunakan karena sudah terdaftar di produk lain'
            ));
        }

        $product_double = Product::whereProduct_code($request['product_code'])
                        ->where('id', '<>', $product->id)->first();
        if ($product_double != null) {
            return back()->with(array(
                        'err' => 'Kode produk tidak bisa digunakan karena sudah terdaftar di produk lain'
            ));
        }

        //Update produk
        if (strlen($request['brand']) > 0) {
            $product->brand_id = $request['brand'];
        }
        if (strlen($request['kategori']) > 0) {
            $product->category_id = $request['kategori'];
        }
        if (strlen($request['subkategori']) > 0) {
            $product->subcategory_id = $request['subkategori'];
        }
        if (strlen($request['barcode']) > 0) {
            $product->barcode = $request['barcode'];
        }
        if (strlen($request['product_code']) > 0) {
            $product->product_code = $request['product_code'];
        }
        if (strlen($request['product_name']) > 0) {
            $product->product_name = $request['product_name'];
        }
        if (strlen($request['desc']) > 0) {
            $product->product_desc = $request['desc'];
        }
        if (strlen($request['weight']) > 0) {
            $product->weight = $request['weight'];
        }
        $product->save();
        
        //hapus dulu productset lama
        foreach($product->sets($product->id) as $product_set){
            $product_set->delete();
        }

        //insert ke product set
        $set_qty = 0;
        $i = 1;
        foreach ($request->product_id as $product_id) {
            $product_qty = Product::find($product_id);
            if($i == 1) $set_qty = $product_qty->qty;
            $productset = new Productset;
            $productset->set_id = $product->id;
            $productset->product_id = $product_id;
            $productset->save();
            
            if($set_qty > $product_qty->qty){
                $set_qty = $product_qty->qty;
            }
            $i++;
        }
        $product->qty = $set_qty;
        $product->save();

        return redirect('productsets')->with('msg', 'Data produk berhasil diubah..');
    }

}
