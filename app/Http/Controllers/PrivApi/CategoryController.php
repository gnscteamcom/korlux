<?php

namespace App\Http\Controllers\PrivApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller {
    
    public function getCategory(Request $request){
        $categories = $request['categories'];
        
        $categories = Category::whereIn('id', $categories)
                ->withTrashed()
                ->select('id', 'category')
                ->get();
        
        $response = [
            'categories' => $categories
        ];
        
        return json_encode($response);
    }

}
