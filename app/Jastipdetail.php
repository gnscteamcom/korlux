<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jastipdetail extends Model
{
    use SoftDeletes;

    protected $table = 'jastipdetails';

    public function jastip() {
      return $this->belongsTo('App\Jastip');
    }
}
