<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Custom\PriceFunction;
use App\Product;

class ScrollController extends Controller {

    public function loadMore(Request $request) {
        $brand_id = $request->brand_id;
        $category_id = $request->category_id;
        $subcategory_id = $request->subcategory_id;
        $sort = $request->sort;
        $search = $request->search;
        $page_number = $request->page_number;

        #ambil data produk
        $products = Product::join('prices', 'products.currentprice_id', '=', 'prices.id');

        #cek apakah ada data brand
        if ($brand_id) {
            $products = $products->where('products.brand_id', '=', $brand_id);
        }

        #cek apakah ada kategori
        if ($category_id) {
            $products = $products->where('products.category_id', '=', $category_id);
        }

        #cek apakah ada subkategori
        if ($subcategory_id) {
            $products = $products->where('products.subcategory_id', '=', $subcategory_id);
        }

        #sort berdasarkan pilihan kalau ada
        switch ($sort) {
            case "new_product": $products = $products->orderBy('products.created_at', 'desc');
            case "new_price": $products = $products->orderBy('products.last_price_update', 'desc');
            case "new_stock": $products = $products->orderBy('products.last_stock_update', 'desc');
            case "most_stock": $products = $products->orderBy('products.qty', 'desc');
            case "name": $products = $products->orderBy('products.product_name');
            case "low_price": $products = PriceFunction::filterPrice($products, 'asc');
            case "high_price": $products = PriceFunction::filterPrice($products, 'desc');
            case "most_buy": $products = $products->orderBy('products.total_buy', 'desc');
            case 0: break;
            default: break;
        }

        #kalau ada search
        if (strlen($search) > 0) {
            if (strcmp($search, 'sale') == 0) {
                $products = $products->where('prices.sale_price', '>', 0);
            } else if (strcmp($search, 'paket') == 0) {
                $products = $products->where('products.is_set', '=', 1);
            } else {
                $products = $products->where('products.product_name', 'like', '%' . $search . '%');
            }
        }

        #finalisasi data produk
        $products = $products
                ->select('products.id', 'products.product_code', 'products.product_name', 'products.product_desc', 'products.qty', 'products.brand_id', 'products.currentprice_id', 'prices.regular_price', 'prices.sale_price', 'products.is_set')
                ->orderBy('prices.sale_price', 'desc')
                ->orderBy('products.product_name')
                ->take(30)
                ->skip($page_number * 30)
                ->get();

        #hitung total produk yang ditampilkan
        $total_data = $products->count();
        
        #siapkan message
        $msg = 'Tidak ada produk lagi untuk ditampilkan.';
        
        #increment page number
        if($total_data > 0){
            $page_number++;
            $msg = 'Menampilkan produk lainnya.';
        }
        
        #siapin datanya
        $products_data[] = null;
        $i = 0;
        foreach($products->chunk(5) as $chunks){
            $each_product[] = null;
            $j = 0;
            foreach($chunks as $chunk){
                $is_wholesale = 0;
                if($chunk->productclasses->count() > 0){
                    $is_wholesale = 1;
                }
                
                $image_path_main = asset('/storage/default.jpg');
                if($chunk->productimages){
                    if($chunk->productimages->first()){
                        $image_path_main = asset($chunk->productimages->first()->image_path);
                    }
                }
                
                $sell_price = PriceFunction::getCurrentPrice($chunk->currentprice_id);
                
                $product_images[] = null;
                $k = 0;
                if ($chunk->productimages->count() > 0) {
                    foreach ($chunk->productimages as $image) {
                        $image_path = asset('/storage/default.jpg');
                        if (strlen($image->image_path) > 0) {
                            $image_path = asset($image->image_path);
                        }

                        $product_images[$k] = [
                            'image_path' => $image_path
                        ];
                        $k++;
                    }
                } else {
                    $product_images[$k] = [
                        'image_path' => asset('/storage/default.jpg')
                    ];
                }

                $product_sets[] = null;
                $l = 0;
                foreach($chunk->sets($chunk->id) as $set){
                    $product_sets[$l] = [
                        'name' => $set->product->product_name
                    ];
                    $l++;
                }
                
                $product_classes[] = null;
                $m = 0;
                foreach($chunk->productclasses as $productclass){
                    $product_classes[$m] = [
                        'min_qty' => $productclass->discountqty->min_qty,
                        'price' => $productclass->discountqty->price,
                        'price_text' => 'Rp. ' . number_format($productclass->discountqty->price, 0, ',', '.')
                    ];
                    $m++;
                }
                
                #hapus semua isi array yang null atau kosong
                $product_images = array_filter($product_images);
                $product_sets = array_filter($product_sets);
                $product_classes = array_filter($product_classes);
                
                $each_product[$j] = [
                    'id' => $chunk->id,
                    'product_code' => $chunk->product_code,
                    'product_name' => $chunk->product_name,
                    'product_desc' => $chunk->product_desc,
                    'brand_name' => $chunk->brand->brand,
                    'qty' => $chunk->qty,
                    'brand_id' => $chunk->brand_id,
                    'regular_price' => $chunk->regular_price,
                    'regular_price_text' => 'Rp. ' . number_format($chunk->regular_price, 0, ',', '.'),
                    'sale_price' => $chunk->sale_price,
                    'sale_price_text' => 'Rp. ' . number_format($chunk->sale_price, 0, ',', '.'),
                    'sell_price' => $sell_price,
                    'sell_price_text' => 'Rp. ' . number_format($sell_price, 0, ',', '.'),
                    'is_wholesale' => $is_wholesale,
                    'productclasses' => $product_classes,
                    'image_path' => $image_path_main,
                    'productimages' => $product_images,
                    'productimages_count' => $chunk->productimages->count(),
                    'is_set' => $chunk->is_set,
                    'set_count' => sizeof($chunk->sets($chunk->id)),
                    'product_sets' => $product_sets
                ];
                $j++;
            }
            $each_product = array_filter($each_product);
            $products_data[$i] = $each_product;
            $i++;
        }

        $response = [
            'data' => $products_data,
            'page_number' => $page_number,
            'count' => $products->count(),
            'msg' => $msg
        ];
        
        return json_encode($response);
    }

}
