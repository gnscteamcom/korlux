<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'brands';
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    
    public function products(){
        
        return $this->hasMany('App\Product');
        
    }
    
    
    public function scopeAvailableProducts($query){
        
        return $this->hasMany('App\Product')
                ->join('prices', 'products.id', '=', 'prices.product_id')
                ->where('prices.valid_date', '<=', \Carbon\Carbon::now())
                ->groupBy('prices.product_id')
                ->get();
        
    }
    
}
