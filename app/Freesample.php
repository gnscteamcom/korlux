<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Freesample extends Model
{
    use SoftDeletes;
    
    protected $table = 'freesamples';
}
