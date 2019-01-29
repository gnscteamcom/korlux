<?php

namespace App\Http\Controllers\PrivApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Price;

class PriceController extends Controller {
    
    public function getPrice(Request $request){
        $prices = $request['prices'];
        
        $prices = Price::whereIn('id', $prices)
                ->withTrashed()
                ->select('id', 'product_id', 'regular_price', 'reseller_1', 'reseller_2', 'valid_date')
                ->get();
        
        $response = [
            'prices' => $prices
        ];
        
        return json_encode($response);
    }

}
