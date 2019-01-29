<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shopeesales extends Model {

    use SoftDeletes;

    protected $table = 'shopeesales';

    public function orderheader() {
        return $this->belongsTo('App\Orderheader');
    }

    public function customeraddress() {
        return $this->belongsTo('App\Customeraddress');
    }

}
