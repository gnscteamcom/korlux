<?php

namespace App\Http\Controllers\PrivApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Subcategory;

class SubcategoryController extends Controller {
    
    public function getSubcategory(Request $request){
        $subcategories = $request['subcategories'];
        
        $subcategories = Subcategory::whereIn('id', $subcategories)
                ->withTrashed()
                ->select('id', 'category_id', 'subcategory')
                ->get();
        
        $response = [
            'subcategories' => $subcategories
        ];
        
        return json_encode($response);
    }

}
