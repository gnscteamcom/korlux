<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Refundrequestdetail extends Model {

    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'refundrequestdetails';

    public function refund() {
        return $this->belongsTo('App\Refundrequest');
    }

    public function orderdetail() {
        return $this->belongsTo('App\Orderdetail');
    }

}
