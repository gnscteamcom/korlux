<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stockrevise extends Model {

    use SoftDeletes;

    protected $table = 'stockrevises';

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function product() {
        return $this->belongsTo('App\Product')->withTrashed();
    }

    public function approveby() {
        return $this->belongsTo('App\User', 'approve_by');
    }

    public function rejectby() {
        return $this->belongsTo('App\User', 'reject_by');
    }

}
