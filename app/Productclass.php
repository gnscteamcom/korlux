<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Productclass extends Model
{

    use SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'productclasses';
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    
    public function scopeProductDelete($query, $id){
        
        return $query->join('products', 'products.id', '=', 'productclasses.product_id')
                ->where('products.id', '=', $id)
                ->withTrashed()->first();
        
    }
    
    
    public function product(){
        
        return $this->belongsTo('App\Product')->withTrashed();
        
    }
    
    
    public function discountqty(){
        
        return $this->belongsTo('App\Discountqty');
        
    }
    
    
    public function userstatus(){
        
        return $this->belongsTo('App\Tablestatus');
        
    }
    
}
