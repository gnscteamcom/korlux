<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Cart;
use App\Priceprocess;
use App\Product;
use App\Usersetting;
use Hash;
use Auth;


class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'doLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'password' => bcrypt($data['password']),
        ]);
    }
    
    
    public function doLogin(Request $request){
        
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required|min:8|max:64'
        ]);
        
            
        //login...
        if(auth()->attempt([
                'username' => $request->input('username'),
                'password' => $request->input('password')
            ])){
            
            $user = auth()->user();
            
            //update stok dari product paket
            if($user->is_owner || $user->is_marketing || $user->is_warehouse || $user->is_finance){
                $this->updateProductSetQty();
            }
            
            //update harga harian
            $this->processCurrentPrice();
            
            if(!$user->is_admin){
                $this->processReseller($user);
                return redirect('home');
            }
            
            if($user->is_owner){
                return redirect('vieworder');
            }else{
                $landing_url = $user->landing_url;
                if(strlen($landing_url) > 0){
                    return redirect($landing_url);
                }else{
                    return redirect('http://koreanluxury.com');
                }
            }
            
        }
        else{

            return back()->withErrors(array('password' => 'User atau password salah'))->withInput($request->except('password'));

        }
            
    }
    
    public function doLoginFB(Request $request) {
        $email = $request->data['email'];
        $fb_id = $request->data['id'];

        #cari dulu by ID
        $user_setting = Usersetting::where('fb_id', 'like', $fb_id)
                ->first();
        if (!$user_setting) {
            $user_setting = Usersetting::where('email', 'like', $email)
                    ->first();
        }
        
        #kalau belum ada berarti belum terdaftar
        if (!$user_setting) {
            $user = new User;
            $user->username = $email;
            $user->password = Hash::make('password');
            $user->name = $request->data['first_name'];
            $user->is_admin = 0;
            $user->is_owner = 0;
            $user->is_marketing = 0;
            $user->is_warehouse = 0;
            $user->save();

            $user_setting = new Usersetting;
            $user_setting->user_id = $user->id;
            $user_setting->first_name = $request->data['first_name'];
            $user_setting->last_name = $request->data['last_name'];
            $user_setting->jenis_kelamin = $request->data['gender'];
            $user_setting->email = $email;
            $user_setting->alamat = '';
            $user_setting->kecamatan_id = 0;
            $user_setting->kecamatan = '';
            $user_setting->kodepos = '';
            $user_setting->hp = '';
            $user_setting->status_id = 1;
            $user_setting->save();
        } else {
            if (strlen($user_setting->fb_id) <= 0) {
                $user_setting->fb_id = $fb_id;
                $user_setting->save();
            }
        }

        #login pakai ID
        Auth::loginUsingId($user_setting->user_id);

        $user = auth()->user();

        //update status reseller
        $this->processReseller($user);

        //update harga harian
        $this->processCurrentPrice();

        //update stok dari product paket
        if ($user->is_owner || $user->is_marketing || $user->is_warehouse || $user->is_finance) {
            $this->updateProductSetQty();
        }

        if ($user->is_owner) {
            return [
                'link' => url('vieworder')
            ];
        } else if ($user->is_marketing) {
            return [
                'link' => url('manualsales')
            ];
        } else if ($user->is_warehouse) {
            return [
                'link' => url('vieworder')
            ];
        } else if ($user->is_finance) {
            return [
                'link' => url('viewpayment')
            ];
        }
        
        if(strlen($user->usersetting->kecamatan) > 0){
            return [
                'link' => url('home')
            ];
        }
        
        return [
            'link' => url('profile')
        ];

    }
    
    
    public function doLogout(){
        
        //hapus cart juga
        Cart::instance('main')->destroy();
        Cart::instance('shipcost')->destroy();
        Cart::instance('discountcoupon')->destroy();
        Cart::instance('discountpoint')->destroy();
        Cart::instance('insurancecost')->destroy();
        Cart::instance('unique')->destroy();
        Cart::instance('packingfee')->destroy();
        
        Cart::instance('manualsalesdata')->destroy();
        Cart::instance('manualsalescart')->destroy();
        
        //logout dan hapus authentication
        auth()->logout();
        return redirect('home');
        
    }
    
    
    private function processReseller($user){

        $usersetting = $user->usersetting;
        
        if($usersetting){
            if($usersetting->status_id < 4){
                \App\Http\Controllers\Custom\UserFunction::upgradeUserStatus();
            }

            if ($user->id != 6830) {
                $usersetting = Usersetting::where('status_id', '>', 1)
                        ->where('status_upgrade_date', '<=', \Carbon\Carbon::now()->subDays(30)->toDateString())
                        ->where('user_id', '=', $user->id)
                        ->first();
                if ($usersetting) {
                    #skip downgrade dulu = 26 July 2017
                    #aktifin tanggal 25 Aug 2017
                    \App\Http\Controllers\Custom\UserFunction::downgradeUserStatus();
                }
            }
        }
    }
    
    private function processCurrentPrice(){
        
        //cek apakah terakhir diupdate itu kemarin
        $priceprocess = Priceprocess::where('id', '=', 1)
                ->where('last_process_date', '<', \Carbon\Carbon::now()->toDateString())
                ->first();
        
        if($priceprocess){
            
            $today = \Carbon\Carbon::today()->toDateString();
            
            //proses harga dengan harga baru
            $products = Product::where('qty', '>', 0)->get();
            foreach($products as $product){
                $product->currentprice_id = \App\Http\Controllers\Custom\PriceFunction::updateCurrentPrice($product->id);
                $product->last_price_update = $today;
                $product->save();
            }
            
            //update tanggal update harga
            $priceprocess->last_process_date = $today;
            $priceprocess->save();
            
        }
        
    }
    
    //update stok dari produk paket (set)
    private function updateProductSetQty() {
        $productsets = Product::where('is_set', '=', 1)->get();

        foreach ($productsets as $set) {
            $i = 1;
            $set_qty = 0;
            foreach ($set->sets($set->id) as $product) {
                $qty = $product->product->qty;
                if ($i == 1)
                    $set_qty = $qty;

                if ($set_qty > $qty) {
                    $set_qty = $qty;
                }
                $i++;
            }

            $set->qty = $set_qty;
            $set->save();
        }
    }

}
