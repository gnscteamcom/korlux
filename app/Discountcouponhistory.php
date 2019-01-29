<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discountcouponhistory extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'discountcouponhistories';
    
    
    public function user(){
        
        return $this->belongsTo('App\User');
        
    }
    
    
    public function discountcoupon(){
        
        return $this->belongsTo('App\Discountcoupon');
        
    }
}
