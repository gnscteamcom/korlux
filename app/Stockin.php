<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stockin extends Model
{
    
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stockins';
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    
    public function scopeProductDelete($query, $id){
        
        return $query->join('products', 'products.id', '=', 'stockins.product_id')
                ->where('products.id', '=', $id)
                ->withTrashed()->first();
        
    }
    
    
    public function product(){
        
        return $this->belongsTo('App\Product');
        
    }
    
}
