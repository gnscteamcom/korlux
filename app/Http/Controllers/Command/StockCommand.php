<?php

namespace App\Http\Controllers\Command;

use App\Http\Controllers\Custom\StockFunction;
use App\Product;

class StockCommand {

    public static function countStockBookSold(){
        $products = Product::where('stock_booked', '<=', 0)
                ->where('stock_sold_30_days', '<=', 0)
                ->get();
        foreach($products as $product){
            $product->stock_booked = StockFunction::getStockBooked($product->id);
            $product->stock_sold_30_days = StockFunction::getStockSold($product->id);
            $product->save();
        }
    }

}
