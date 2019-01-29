<?php

namespace App\Http\Controllers\PrivApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Brand;

class BrandController extends Controller {
    
    public function getBrand(Request $request){
        $brands = $request['brands'];
        
        $brands = Brand::whereIn('id', $brands)
                ->withTrashed()
                ->select('id', 'brand')
                ->get();
        
        $response = [
            'brands' => $brands
        ];
        
        return json_encode($response);
    }

}
