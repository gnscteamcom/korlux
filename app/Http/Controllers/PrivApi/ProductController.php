<?php

namespace App\Http\Controllers\PrivApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;

class ProductController extends Controller {

    public function allProducts(Request $request) {
        $products = Product::join('prices', 'prices.id', '=', 'products.currentprice_id')
                ->join('brands', 'brands.id', '=', 'products.brand_id')
                ->where('products.is_set', '=', 0)
                ->where('products.qty', '>=', 0)
                ->select('products.id as id', 'brands.brand as brand_name', 'products.product_name as product_name', 'products.qty as qty',
                        'prices.reseller_2 as vip_price', 'prices.regular_price as regular_price')
                ->orderBy('products.id')
                ->get();

        return json_encode($products);
    }

    public function products(Request $request) {
        $last_id = $request['product_id'];
        $count = $request['count'];
        if (!$count) {
            $count = 100;
        }

        if (!$last_id) {
            $products = Product::orderBy('id')
                            ->take($count)->get();
        } else {
            $products = Product::where('id', '>', $last_id)
                            ->orderBy('id')
                            ->take($count)->get();
        }

        $response = [
            'products' => $products
        ];

        return json_encode($response);
    }

    public function product(Request $request) {
        $product_id = $request['product_id'];

        $product = Product::where('id', '=', $product_id)
                ->withTrashed()
                ->first();

        $response = [
            'product' => $product
        ];

        return json_encode($product);
    }

    public function getCurrentPriceId(Request $request) {
        $products = $request['products'];

        $products = Product::whereIn('id', $products)
                ->withTrashed()
                ->select('id', 'currentprice_id')
                ->get();

        $response = [
            'products' => $products
        ];

        return json_encode($response);
    }

}
