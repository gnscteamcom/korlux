<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model {

    protected $table = 'menus';

    public function submenus() {
        return $this->hasMany('App\Submenu')->orderBy('submenus.position');
    }

}
