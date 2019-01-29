<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    
    use SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    
    public function products(){
        
        return $this->hasMany('App\Product');
        
    }
    
    
    public function subcategories(){
        
        return $this->hasMany('App\Subcategory');
        
    }
    
    
    public function scopeAvailableProducts($query){
        
        return $this->hasMany('App\Product')
                ->join('prices', 'products.id', '=', 'prices.product_id')
                ->where('prices.valid_date', '<=', \Carbon\Carbon::now())
                ->groupBy('prices.product_id')
                ->get();
        
    }
    
    
}
