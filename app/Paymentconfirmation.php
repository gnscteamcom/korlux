<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paymentconfirmation extends Model
{
    
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paymentconfirmations';
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    
    public function orderheader(){
        
        return $this->belongsTo('App\Orderheader');
        
    }
    
    
    public function user(){
        
        return $this->belongsTo('App\User');
        
    }
    
    
    public function bank(){
        
        return $this->belongsTo('App\Bank')->withTrashed();
        
    }

    public function paymentconfirmations() {
        return $this->hasMany('App\Paymentconfirmation');
    }

}
