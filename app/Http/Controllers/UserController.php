<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ShipmentApi;
use App\User;
use App\Usersetting;
use App\Orderheader;
use App\Tablestatus;
use App\Menu;
use Hash;
use Mail;

class UserController extends Controller {


    public function viewAllUser(){

        $users = User::where('id', '>=', 3)->orderBy('username')->paginate(50);
        $statuses = Tablestatus::whereBetween('id', [1, 4])
                ->get();

        return view('pages.admin-side.modules.user.viewuser')->with(array(
            'users' => $users,
            'statuses' => $statuses
        ));

    }


    public function viewUserDetail($user_id){

        $user = User::find($user_id);
        $pointhistories = $user->pointhistories;
        $orderheaders = Orderheader::whereUser_id($user->id)->orderBy('updated_at', 'desc')->paginate(100);

        #Ambil daftar menus
        $menus = Menu::orderBy('position')
                ->get();

        return view('pages.admin-side.modules.user.viewuserdetail')->with(array(
            'user' => $user,
            'pointhistories' => $pointhistories,
            'orderheaders' => $orderheaders,
            'menus' => $menus
        ));

    }

    public function updateUserConfig(Request $request){
        $this->validate($request, [
            'nama' => 'required'
        ]);

        $user = User::find($request->user_id);
        if ($user) {
            if (strlen($request->landing_url) > 0) {
                $user->landing_url = $request->landing_url;
                $user->save();
            }

            $usersetting = $user->usersetting;
            $usersetting->first_name = $request->nama;
            $usersetting->save();
        }

        return back()->with([
                    'msg' => 'Berhasil update konfigurasi dasar user.'
        ]);
    }

    public function changePassword(){

        return view('pages.admin-side.modules.user.changepassword');

    }


    public function updatePassword(Request $request){

        $this->validate($request, [
            'oldpassword' => 'required|min:8|max:32',
            'newpassword' => 'required|min:8|max:32',
            'confpassword' => 'required|min:8|max:32'
        ]);


        $user = User::find(auth()->user()->id);

        //Validasi apakah password yang diketikkan sama dengan password lama
        if(!Hash::check($request['oldpassword'], $user->password)){
            return back()->withErrors(array('oldpassword' => 'Password lama salah..'))->withInput($request->except('newpassword', 'confpassword', 'oldpassword'));
        }

        //Validasi kesamaan pengetikan Password dan confirm password
        if(strcmp($request['newpassword'], $request['confpassword']) != 0){
            return back()->withErrors(array('confpassword' => 'Konfirmasi password salah..'))->withInput($request->except('newpassword', 'confpassword', 'oldpassword'));
        }

        //Update passwordnya user
        $user->password = Hash::make($request['newpassword']);
        $user->save();

        return back()->with(array('msg' => 'Password berhasil diubah..'));


    }


    public function viewRegister() {
        $kecamatans = ShipmentApi::kecamatans();

        return view('pages.front-end.register')->with([
                    'kecamatans' => $kecamatans['data'],
                    'kecamatan_count' => $kecamatans['count'],
        ]);
    }

    public function registerUser(Request $request){

        $this->validate($request, [
            'nama_depan' => 'required|max:32',
            'nama_belakang' => 'max:32',
            'email' => 'required|email|max:48',
            'jenis_kelamin' => 'required',
            'alamat' => 'required',
            'kecamatan' => 'required',
            'kodepos' => 'required|max:6',
            'hp' => 'required|max:16',
            'username' => 'required|unique:users|max:16|alpha_num',
            'password' => 'required|min:8|max:64',
            'konfirmasi_password' => 'required|min:8|max:64',
        ]);

        //validasi password dengan konfirmasi tidak sama
        if(strcmp($request['password'], $request['konfirmasi_password']) != 0){
            return back()->withErrors(array('konfirmasi_password' => 'Konfirmasi password anda salah...'))->withInput($request->except('konfirmasi_password'));
        }


        $user = User::whereUsername($request['username'])->first();
        if($user != null){
            return back()->withErrors(array(
                'username' => 'Username sudah dipakai'
            ))->withInput();
        }


        $usersetting = Usersetting::whereEmail($request['email'])->first();
        if($usersetting != null){
            return back()->withErrors(array(
                'email' => 'Email sudah dipakai'
            ))->withInput();
        }



        //Insert user baru
        $user = new User;
        $user->username = $request['username'];
        $user->password = Hash::make($request['password']);
        $user->name = $request['nama_depan'];
        $user->is_admin = 0;
        $user->is_owner = 0;
        $user->is_marketing = 0;
        $user->is_warehouse = 0;
        $user->save();


        //Insert ke usersettings
        $user_setting = new Usersetting;
        $user_setting->user_id = $user->id;
        $user_setting->first_name = $request['nama_depan'];
        $user_setting->last_name = $request['nama_belakang'];
        $user_setting->jenis_kelamin = $request['jenis_kelamin'];
        $user_setting->email = $request['email'];
        $user_setting->alamat = $request['alamat'];
        $user_setting->kecamatan_id = $request['kecamatan'];
        $user_setting->kecamatan = $request['kecamatan_text'];
        $user_setting->kodepos = $request['kodepos'];
        $user_setting->hp = $request['hp'];
        $user_setting->status_id = 1;
        $user_setting->save();

        return redirect('login')->with(array('msg' => 'Pendaftaran berhasil, mohon login..'));

    }


    public function updateName(Request $request){

        $this->validate($request, [
            'first_name' => 'required|max:32',
            'last_name' => 'max:32'
        ]);


        //update nama di user
        $user = auth()->user();
        $user->name = $request['first_name'];
        $user->save();

        //update usersetting
        $user_setting = $user->usersetting;
        if(!$user_setting){
            $user_setting = new Usersetting;
            $user_setting->user_id = $user->id;
        }
        $user_setting->first_name = $request['first_name'];
        $user_setting->last_name = $request['last_name'];
        $user_setting->save();

        return back()->with(array('msg' => 'Nama berhasil diubah..'));

    }


    public function updateProfile(Request $request){
        $this->validate($request, [
            'email' => 'email|max:48',
            'jenis_kelamin' => 'required',
            'alamat' => 'required',
            'kecamatan' => 'required',
            'kodepos' => 'required|max:6',
            'hp' => 'required|max:16'
        ]);

        //Insert ke usersettings
        $user_setting = auth()->user()->usersetting;

        if(!$user_setting){
            $user_setting = new Usersetting;
            $user_setting->user_id = auth()->user()->id;
        }

        $user_setting->jenis_kelamin = Custom\UpdateFunction::useOldOrNew($request['jenis_kelamin'], $user_setting->jenis_kelamin);
        $user_setting->email = Custom\UpdateFunction::useOldOrNew($request['email'], $user_setting->email);
        $user_setting->alamat = Custom\UpdateFunction::useOldOrNew($request['alamat'], $user_setting->alamat);
        if(strlen($request->kecamatan_text) > 0){
            $user_setting->kecamatan_id = $request->kecamatan;
            $user_setting->kecamatan = $request->kecamatan_text;
        }
        $user_setting->kodepos = Custom\UpdateFunction::useOldOrNew($request['kodepos'], $user_setting->kodepos);
        $user_setting->hp = Custom\UpdateFunction::useOldOrNew($request['hp'], $user_setting->hp);
        $user_setting->save();

        return back()->with(array('msg' => 'Profil berhasil diubah..'));

    }


    public function updateUserStatus(Request $request) {
        $status_id = $request->status_id;
        $user_id = $request->user_id;
        $user = User::find($user_id);

        if (!$user) {
            $response = [
                'result' => 0,
                'msg' => 'Tidak ada data pengguna yang ditemukan.'
            ];
            return json_encode($response);
        }

        $user_setting = $user->usersetting;
        $user_setting->status_id = $status_id;
        $user_setting->status_upgrade_date = \Carbon\Carbon::now()->toDateString();
        $user_setting->save();

        $response = [
            'result' => 1,
            'msg' => 'Berhasil mengubah status ' . $user->username . ' menjadi ' . $user_setting->status->status
        ];

        return json_encode($response);
    }

    public function resetPassword(Request $request){

        $this->validate($request, [
            'email' => 'email|max:48',
            'username' => 'required|max:48|alpha_num'
        ]);


        $user = User::whereUsername($request['username'])->first();

        if(!$user){
            return back()->with(array(
                'err' => 'Username salah..'
            ));
        }
        $usersetting = $user->usersetting;

        //validasi apakah email dan hp nya cocok atau tidak..
        if(strcmp($request['email'], $usersetting->email) != 0){
            return back()->with('err', 'Data salah..');
        }

        //Kirim email yang ada passwordnya
        $password = Custom\StringFunction::generateRandomString(10);
        $message_data = [
            'current_password' => $password
        ];
        $damn = Mail::send('emails.resetpassword', $message_data, function ($message) use ($usersetting) {
            $message->from('noreply@koreanluxury.com', 'Koreanluxury');
            $message->to($usersetting->email, $name = null);
            $message->replyTo('noreply@koreanluxury.com', 'Koreanluxury');
            $message->subject('[Koreanluxury] RESET PASSWORD - Jangan dibalas !!');
        });

        //Update passwordnya user
        $user->password = Hash::make($password);
        $user->save();

        return redirect('login')->with(array(
            'msg' => 'Password anda berhasil direset.. Silahkan periksa email Anda..'
        ));

    }

    public function resetUsername(Request $request) {
        $this->validate($request, [
            'email' => 'email|max:48'
        ]);

        $user = User::join('usersettings', 'usersettings.user_id', '=', 'users.id')
                ->where('usersettings.email', 'like', $request->email)
                ->select('users.*')
                ->first();
        if (!$user) {
            return back()->with([
                        'err' => 'Tidak ada data.'
            ]);
        }

        return redirect('login')->with([
                    'msg' => 'Username Anda adalah : ' . $user->username . '.'
        ]);
    }

    public function searchUser(Request $request){

        $users = User::join('usersettings', 'usersettings.user_id', '=', 'users.id')
                ->where('users.username', 'like', '%' . $request['search'] . '%')
                ->orWhere('usersettings.email', 'like', '%' . $request['search'] . '%')
                ->where('users.id', '>=', 3)
                ->orderBy('users.name');
        $statuses = Tablestatus::whereBetween('id', [1, 4])
                ->get();

        $counter = $users->count();

        $users = $users->select('users.*')->paginate($counter);

        return view('pages.admin-side.modules.user.viewuser')->with(array(
            'users' => $users,
            'statuses' => $statuses
        ));

    }


}
