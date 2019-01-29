<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ordermarketplace extends Model {

    use SoftDeletes;

    protected $table = 'ordermarketplaces';

    public function orderheader() {
        return $this->belongsTo('App\Orderheader');
    }

}
