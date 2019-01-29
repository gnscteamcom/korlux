<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Usermenu;

class RuleController extends Controller {

    public function setIsAdmin(Request $request) {
        $user_id = $request->user_id;
        $is_admin = $request->is_admin;

        $user = User::find($user_id);
        $user->is_admin = $is_admin;
        $user->save();

        $msg = 'Pengguna : ' . $user->username;
        if (!$is_admin) {
            $msg .= ' tidak';
        }
        $msg .= ' bisa login sebagai admin';

        $response = [
            'msg' => $msg
        ];

        return json_encode($response);
    }

    public function setIsOwner(Request $request) {
        $user_id = $request->user_id;
        $is_owner = $request->is_owner;

        $user = User::find($user_id);
        $user->is_owner = $is_owner;
        $user->save();

        $msg = 'Pengguna : ' . $user->username;
        if (!$is_owner) {
            $msg .= ' bukan';
        }
        $msg .= ' OWNER.';

        $response = [
            'msg' => $msg
        ];

        return json_encode($response);
    }

    public function setMenuAccess(Request $request) {
        $user_id = $request->user_id;
        $is_delete = $request->is_delete;
        $menu_id = $request->menu_id;
        $submenu_id = $request->submenu_id;

        $usermenu = Usermenu::where('user_id', '=', $user_id)
                ->where('menu_id', '=', $menu_id)
                ->where('submenu_id', '=', $submenu_id)
                ->withTrashed()
                ->first();
        
        $user = User::find($user_id);

        $msg = 'Pengguna : ' . $user->username;

        #cek dulu apakah ada
        #kalau tidak, create baru
        if (!$usermenu) {
            $usermenu = new Usermenu;
            $usermenu->user_id = $user_id;
        }

        #kalau ada, cek dulu mau delete atau tidak
        if ($is_delete) {
            $usermenu->is_active = 0;
            $msg .= ' tidak';
        } else {
            $usermenu->is_active = 1;
            $usermenu->menu_id = $menu_id;
            $usermenu->submenu_id = $submenu_id;
        }
        $usermenu->save();
        
        $menu_name = '';
        if($usermenu->menu){
            $menu_name = $usermenu->menu->menu;
        }
        $submenu_name = '';
        if($usermenu->submenu){
            $submenu_name = $usermenu->submenu->submenu;
        }
        $msg .= ' bisa akses: ' . $menu_name . ' - ' . $submenu_name . '.';

        $response = [
            'msg' => $msg
        ];

        return json_encode($response);
    }

}
