<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Custom\StockBalanceFunction;
use App\Stockhistory;
use App\Orderdetail;

class CapitalFunction {


    public static function stockCorrection($product, $change_qty){

        //cek dulu stok terbook saat ini ada berapa
        $stock_booked = Orderdetail::join('orderheaders', 'orderheaders.id', '=', 'orderdetails.orderheader_id')
                ->where('orderheaders.status_id', '<', '15')
                ->where('orderdetails.product_id', '=', $product->id)
                ->sum('orderdetails.qty');

        //qty asli ditotalin
        $real_qty = $stock_booked + $product->qty;

        //cek apakah qty baru nanti lebih kecil dari 0
        if($change_qty - $stock_booked < 0){
            return true;
        }

        //save ke stok history
        $stock_history = new Stockhistory;
        $stock_history->product_id = $product->id;
        $stock_history->initial_qty = $real_qty;
        $stock_history->change_qty = $change_qty;
        $stock_history->initial_capital = 0;
        $stock_history->change_capital = 0;
        $stock_history->user_id = auth()->user()->id;
        $stock_history->save();

        $product->qty = $change_qty - $stock_booked;
        $product->last_stock_update = \Carbon\Carbon::now()->toDateString();

        #update stok balance
        $stock_in = 0;
        $stock_out = 0;
        if($change_qty < 0){
            $stock_out = abs($change_qty);
        }else{
            $stock_in = $change_qty;
        }
        StockBalanceFunction::addBalance($product->id, $stock_in, 0, $stock_out, "Koreksi Stok. ID Histori Stok: " . $stock_history->id);
        $product->save();

    }

}
