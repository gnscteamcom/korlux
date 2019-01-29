<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stockbalance extends Model {

    use SoftDeletes;

    protected $table = 'stockbalances';

    public function product() {
        return $this->belongsTo('App\Product');
    }

}
