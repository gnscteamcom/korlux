<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Externallink extends Model {

    use SoftDeletes;

    protected $table = 'externallinks';

}
