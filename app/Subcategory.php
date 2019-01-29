<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subcategory extends Model
{
    
    use SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subcategories';
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    
    public function products(){
        
        return $this->hasMany('App\Product');
        
    }
    
    
    public function category(){
        
        return $this->belongsTo('App\Category')->withTrashed();
        
    }
    
    
    public function scopeAvailableProducts($query){
        
        return $this->hasMany('App\Product')
                ->join('prices', 'products.id', '=', 'prices.product_id')
                ->where('prices.valid_date', '<=', \Carbon\Carbon::now())
                ->groupBy('prices.product_id')
                ->get();
        
    }
}
