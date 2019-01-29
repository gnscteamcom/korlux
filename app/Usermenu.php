<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Usermenu extends Model {

    use SoftDeletes;

    protected $table = 'usermenus';

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function menu() {
        return $this->belongsTo('App\Menu');
    }

    public function submenu() {
        return $this->belongsTo('App\Submenu');
    }

}
