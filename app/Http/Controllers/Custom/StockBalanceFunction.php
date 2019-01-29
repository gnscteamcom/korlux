<?php

namespace App\Http\Controllers\Custom;

use App\Stockbalance;
use App\Product;

class StockBalanceFunction {

    public static function addBalance($product_id, $stock_in, $stock_booked, $stock_out, $notes) {
        $product = Product::find($product_id);
        
        $current_stock = $product->qty;
        $stock_total = $current_stock + $stock_in - $stock_out;
        $stock_system = $current_stock + $stock_in - $stock_out - $stock_booked;

        #save ke balance
        $stockbalance = new Stockbalance;
        $stockbalance->product_id = $product_id;
        $stockbalance->current_stock = $current_stock;
        $stockbalance->stock_in = $stock_in;
        $stockbalance->stock_booked = $stock_booked;
        $stockbalance->stock_out = $stock_out;
        $stockbalance->stock_total = $stock_total;
        $stockbalance->stock_system = $stock_system;
        $stockbalance->notes = $notes;
        $stockbalance->save();
    }

}
