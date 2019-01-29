<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Productimage extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'productimages';
    
    
    public function product(){
        
        return $this->belongsTo('App\Product');
        
    }
    
}