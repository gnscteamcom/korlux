<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pointhistory extends Model
{
    
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pointhistories';
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    
    public function user(){
        
        return $this->belongsTo('App\User');
        
    }
    
    
    public function orderheader(){
        
        return $this->belongsTo('App\Orderheader');
        
    }
    
}
