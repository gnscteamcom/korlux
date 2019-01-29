<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Packingfeecargo extends Model
{
    use SoftDeletes;

    protected $table = 'packingfeecargos';
}
