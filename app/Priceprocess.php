<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Priceprocess extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'priceprocesses';
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}
