<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservedstockhistory extends Model {

    use SoftDeletes;

    protected $table = 'reservedstockhistories';

    public function order() {
        return $this->belongsTo('App\Orderheader');
    }

}
