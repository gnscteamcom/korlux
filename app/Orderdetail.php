<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Orderdetail extends Model
{
    
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'orderdetails';
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    
    public function orderheader(){
        
        return $this->belongsTo('App\Orderheader');
        
    }
    
    
    public function scopeProductDelete($query, $id){
        
        return $query->join('products', 'products.id', '=', 'orderdetails.product_id')
                ->where('products.id', '=', $id)
                ->withTrashed()->first();
        
    }
    
    
    public function product(){
        
        return $this->belongsTo('App\Product');
        
    }
    
    
    public function scopeProductWithTrashed($query, $product_id){
        
        $product = Product::withTrashed()->whereId($product_id)->first();
        
        return $product;
        
    }
        
}
