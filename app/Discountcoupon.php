<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discountcoupon extends Model {

    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'discountcoupons';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function status() {
        return $this->belongsTo('App\Tablestatus', 'available_for_status');
    }

    public function user() {
        return $this->belongsTo('App\User', 'only_for_user');
    }

}
