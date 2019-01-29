<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Productset extends Model {
    use SoftDeletes;
    
    protected $table = 'productsets';
    
    public function product(){
        return $this->belongsTo('App\Product')->withTrashed();
    }
    
    public function set(){
        return $this->belongsTo('App\Product')->withTrashed();
    }
    
}
