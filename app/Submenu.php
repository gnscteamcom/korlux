<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Submenu extends Model {

    protected $table = 'submenus';

    public function menu() {
        return $this->belongsTo('App\Menu');
    }

}
