<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Orderheader extends Model {

    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'orderheaders';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function status() {

        return $this->belongsTo('App\Tablestatus');
    }

    public function refundrequest() {
        return $this->hasOne('App\Refundrequest', 'order_id')->orderBy('created_at', 'desc');
    }

    public function orderdetails() {

        return $this->hasMany('App\Orderdetail');
    }

    public function pointhistory() {

        return $this->hasOne('App\Pointhistory');
    }

    public function user() {

        return $this->belongsTo('App\User');
    }

    public function paymentconfirmation() {

        return $this->hasOne('App\Paymentconfirmation');
    }

    public function customeraddress() {

        return $this->belongsTo('App\Customeraddress')->withTrashed();
    }

    public function dropship() {

        return $this->belongsTo('App\Dropship')->withTrashed();
    }

    public function discountcoupon() {

        return $this->belongsTo('App\Discountcoupon');
    }

    public function shopeesales() {
        return $this->hasOne('App\Shopeesales')->withTrashed();
    }

    public function orderheaderhistories() {
        return $this->hasMany('App\Orderheaderhistory')->orderBy('created_at', 'desc');
    }

    public function cancelby() {
        return $this->belongsTo('App\User', 'cancel_by');
    }

    public function processby() {
        return $this->belongsTo('App\User', 'process_by');
    }

    public function acceptby() {
        return $this->belongsTo('App\User', 'accept_by');
    }

    public function ordermarketplace() {
        return $this->hasOne('App\Ordermarketplace');
    }

}
