<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jastip extends Model
{
    use SoftDeletes;

    protected $table = 'jastips';

    public function jastipdetails() {
      return $this->hasMany('App\Jastipdetail')->orderBy('product_name', 'asc');
    }

    public function user() {
      return $this->belongsTo('App\User');
    }

    public function customeraddress() {
      return $this->belongsTo('App\Customeraddress');
    }
}
