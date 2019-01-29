<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Packingfee extends Model {

    use SoftDeletes;

    protected $table = 'packingfees';

}
