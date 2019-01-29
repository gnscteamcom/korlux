<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipcost extends Model {

    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shipcosts';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function kecamatan() {

        return $this->belongsTo('App\Kecamatan');
    }

    public function shipmethod() {

        return $this->belongsTo('App\Shipmethod');
    }

}
