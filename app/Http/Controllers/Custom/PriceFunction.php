<?php

namespace App\Http\Controllers\Custom;
use App\Price;
use App\Product;

class PriceFunction {


    public static function updateDailyPrice(){

        $today = \Carbon\Carbon::today()->toDateString();

        //proses harga dengan harga baru
        $products = Product::where('qty', '>=', 0)->get();
        foreach($products as $product){
            $product->currentprice_id = PriceFunction::updateCurrentPrice($product->id);
            $product->last_price_update = $today;
            $product->save();
        }

    }

    public static function getPriceByStatus($price_id, $status_id){
        $price = Price::find($price_id);
        switch($status_id){
            case 1: return $price->regular_price;
            case 2: return $price->reseller_1;
            case 3: return $price->reseller_2;
            case 4: return $price->vvip;
            default: return $price->regular_price;
        }
    }


    public static function getCurrentPrice($price_id, $user_status = 1){

        if(auth()->check()){
            if(auth()->user()->usersetting){
                $user_status = auth()->user()->usersetting->status_id;
            }
        }

        //kalau tidak ada ID price, balikin 0 sebagai harga
        if($price_id == 0){
            return 0;
        }

        //ambil harga sale
        $sale_price = Price::withTrashed()
                ->where('id', '=', $price_id)
                ->first();

        $sale_price = $sale_price->sale_price;

        //kalau harga sale ada, langsung balikin harga sale aja.
        if($sale_price > 0){
            return $sale_price;
        }

        $query = Price::where('id', '=', $price_id);

        switch($user_status){
            case 1:
                $query = $query->select('regular_price as price')->first();
                break;
            case 2:
                $query = $query->select('reseller_1 as price')->first();
                break;
            case 3:
                $query = $query->select('reseller_2 as price')->first();
                break;
            case 4:
                $query = $query->select('vvip as price')->first();
                break;
            default:
                $query = $query->select('regular_price as price')->first();
                break;
        }

        if($query == null){
            $result = 0;
        }
        else{
            $result = $query->price;
        }

        return $result;

    }

    public static function filterPrice($products, $filter_order){

        $user_status = 1;
        if(auth()->check()){
            if(auth()->user()->usersetting){
                $user_status = auth()->user()->usersetting->status_id;
            }
        }

        switch($user_status){
            case 1:
                $products = $products->orderBy('regular_price', $filter_order);
                break;
            case 2:
                $products = $products->orderBy('reseller_1', $filter_order);
                break;
            case 3:
                $products = $products->orderBy('reseller_2', $filter_order);
                break;
        }

        return $products;

    }


    public static function updateCurrentPrice($product_id){

        $query = Product::join('prices', 'prices.product_id', '=', 'products.id')
                ->where('products.id', '=', $product_id)
                ->where('prices.valid_date', '<=', \Carbon\Carbon::now())
                ->where('prices.deleted_at', '=', NULL)
                ->orderBy('prices.valid_date', 'desc')
                ->select('prices.id')->first();

        if($query){
            return $query->id;
        }
        return 0;

    }


}
