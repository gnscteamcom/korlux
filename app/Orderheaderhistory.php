<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Orderheaderhistory extends Model {

    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'orderheaderhistories';

    public function paymentconfirmation() {
        return $this->hasOne('App\Paymentconfirmation', 'orderheader_id');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function customeraddress() {
        return $this->belongsTo('App\Customeraddress')->withTrashed();
    }

    public function dropship() {
        return $this->belongsTo('App\Dropship')->withTrashed();
    }

    public function status() {
        return $this->belongsTo('App\Tablestatus');
    }

    public function orderdetailhistories() {
        return $this->hasMany('App\Orderdetailhistory');
    }

}
