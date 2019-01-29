<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Refundrequest extends Model {

    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'refundrequests';

    public function order() {
        return $this->belongsTo('App\Orderheader');
    }

    public function status() {
        return $this->belongsTo('App\Refundstatus');
    }
    
    public function refundrequestdetails(){
        return $this->hasMany('App\Refundrequestdetail', 'refund_id');
    }

}
