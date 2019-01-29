<?php

namespace App\Http\Controllers\PrivApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Productimage;

class ProductImageController extends Controller {

    public function productImages(Request $request) {
        $productimages = Productimage::orderBy('product_id')
                ->select('id', 'product_id', 'image_path')
                ->get();

        return json_encode($productimages);
    }

}
