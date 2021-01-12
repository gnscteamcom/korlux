<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Custom\UserFunction;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Cart;
use App\Priceprocess;
use App\Product;
use App\Usersetting;
use App\ResellerConfig;
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
      $resellerconfig = ResellerConfig::first();

      $upgrade_days = 0;
      $downgrade_days = 0;
      $upgrade_nominal = 0;
      $downgrade_nominal = 0;
      $status_upgrade = 1;
      $status_downgrade = 1;

      switch($usersetting->status_id) {
        case 1:
          $upgrade_days = $resellerconfig->silver_upgrade_days;
          $downgrade_days = 0;
          $upgrade_nominal = $resellerconfig->silver_min_upgrade;
          $downgrade_nominal = 0;
          $status_upgrade = 2;
          $status_downgrade = 1;
          break;
        case 2:
          $upgrade_days = $resellerconfig->gold_upgrade_days;
          $downgrade_days = $resellerconfig->silver_downgrade_days;
          $upgrade_nominal = $resellerconfig->gold_min_upgrade;
          $downgrade_nominal = $resellerconfig->silver_min_downgrade;
          $status_upgrade = 3;
          $status_downgrade = 1;
          break;
        case 3:
          $upgrade_days = $resellerconfig->platinum_upgrade_days;
          $downgrade_days = $resellerconfig->gold_downgrade_days;
          $upgrade_nominal = $resellerconfig->platinum_min_upgrade;
          $downgrade_nominal = $resellerconfig->gold_min_downgrade;
          $status_upgrade = 3;
          $status_downgrade = 2;
          break;
        case 4:
          $upgrade_days = 0;
          $downgrade_days = $resellerconfig->platinum_downgrade_days;
          $upgrade_nominal = 0;
          $downgrade_nominal = $resellerconfig->platinum_min_downgrade;
          $status_upgrade = 4;
          $status_downgrade = 3;
          break;
        default:
            break;
      }


      //UPGRADE
      $subject = "";
      $email_message = "";
      if($upgrade_days > 0) {
        $grand_total = UserFunction::getShopValue($upgrade_days, 0, $user->id);

        if($grand_total >= $upgrade_nominal) {
          //update user status
          $usersetting->status_id = $status_upgrade;

          switch ($status_upgrade) {
            case 4:
              $subject = 'Selamat!! kamu sudah menjadi PLATINUM Reseller';
              $email_message = "SELAMAT!!!!\r\n\r\n"
                      . "Karena sudah mencapai target pembelian, Anda telah menjadi PLATINUM reseller. Harga yang tertera di http://www.koreanluxury.com akan lebih murah dari harga sebelumnya..\r\n\r\n"
                      . "PLATINUM RESELLER adalah tingkat reseller teringgi. Untuk informasi yang berkaitan dengan reseller, silahkan klik: http://www.koreanluxury.com/reseller \r\n\r\n"
                      . "Jika BELUM PERNAH melakukan pendaftaran reseller via email, mohon segera lakukan pendaftaran untuk mendapatkan pricelist reseller, dan info-info lainnya. Kirim email ke: yeppeneshop@gmail.com \r\n\r\n"
                      . "Subject: pendaftaran reseller langsung\r\n"
                      . "Isi email:\r\n\r\n"
                      . "username: (tulis email jika pendaftaran melalui facebook)\r\n"
                      . "Nama lengkap OWNER:\r\n"
                      . "Nama OLSHOP:\r\n"
                      . "Nomer handphone:\r\n"
                      . "Line ID:\r\n"
                      . "Instagram:\r\n\r\n"
                      . "Best Regards,\r\n"
                      . "www.koreanluxury.com\r\n";
              break;
            case 3:
              $subject = 'Selamat!! kamu sudah menjadi GOLD Reseller';
              $email_message = "SELAMAT!!!!\r\n\r\n"
                      . "Karena sudah mencapai target pembelian, Anda telah menjadi GOLD reseller. Harga yang tertera di http://www.koreanluxury.com akan lebih murah dari harga sebelumnya..\r\n\r\n"
                      . "Terus tingkatkan pembelian anda untuk menjadi PLATINUM RESELLER. Untuk informasi yang berkaitan dengan reseller, silahkan klik: http://www.koreanluxury.com/reseller \r\n\r\n"
                      . "Jika BELUM PERNAH melakukan pendaftaran reseller via email, mohon segera lakukan pendaftaran untuk mendapatkan pricelist reseller, dan info-info lainnya. Kirim email ke: yeppeneshop@gmail.com \r\n\r\n"
                      . "Subject: pendaftaran reseller langsung\r\n"
                      . "Isi email:\r\n\r\n"
                      . "username: (tulis email jika pendaftaran melalui facebook)\r\n"
                      . "Nama lengkap OWNER:\r\n"
                      . "Nama OLSHOP:\r\n"
                      . "Nomer handphone:\r\n"
                      . "Line ID:\r\n"
                      . "Instagram:\r\n\r\n"
                      . "Best Regards,\r\n"
                      . "www.koreanluxury.com\r\n";
              break;
            case 2:
              $subject = 'Selamat!! kamu sudah menjadi SILVER Reseller';
              $email_message = "SELAMAT!!!!\r\n\r\n"
                      . "Karena sudah mencapai target pembelian, Anda telah menjadi SILVER reseller. Harga yang tertera di http://www.koreanluxury.com akan lebih murah dari harga sebelumnya..\r\n\r\n"
                      . "Terus tingkatkan pembelian anda untuk menjadi GOLD RESELLER. Untuk informasi yang berkaitan dengan reseller, silahkan klik: http://www.koreanluxury.com/reseller \r\n\r\n"
                      . "Jika BELUM PERNAH melakukan pendaftaran reseller via email, mohon segera lakukan pendaftaran untuk mendapatkan pricelist reseller, dan info-info lainnya. Kirim email ke: yeppeneshop@gmail.com \r\n\r\n"
                      . "Subject: pendaftaran reseller langsung\r\n"
                      . "Isi email:\r\n\r\n"
                      . "username: (tulis email jika pendaftaran melalui facebook)\r\n"
                      . "Nama lengkap OWNER:\r\n"
                      . "Nama OLSHOP:\r\n"
                      . "Nomer handphone:\r\n"
                      . "Line ID:\r\n"
                      . "Instagram:\r\n\r\n"
                      . "Best Regards,\r\n"
                      . "www.koreanluxury.com\r\n";
              break;
            default:
              break;
          }

        }
        if(strlen($subject) > 0) {
          $usersetting->status_upgrade_date = \Carbon\Carbon::now()->toDateString();
          $usersetting->save();

          UserFunction::processOrderheader($upgrade_days, $user->id);
          OrderFunction::sendEmail($email_message, $subject, $user->usersetting->email);
        }
      }


      //DOWNGRADE
      $subject = "";
      $email_message = "";
      if($downgrade_days > 0) {
        $grand_total = UserFunction::getShopValue($downgrade_days, false, $user->id);

        if($grand_total <= $downgrade_nominal) {
          //update user status downgrade
          $usersetting->status_id = $status_downgrade;

          switch ($status_downgrade) {
            case 1:
              $subject = 'Maaf status SILVER kamu sudah BERAKHIR';
              $email_message = "Hello, karena sudah 1 bulan kamu tidak melakukan order maka status SILVER kamu sudah BERAKHIR..\r\n"
                      . "Sekarang harga yang tertera di www.koreanluxury.com adalah harga NORMAL..\r\n"
                      . "Untuk informasi yang berkaitan dengan reseller, silahkan klik: http://www.koreanluxury.com/reseller \r\n\r\n"
                      . "Best Regards,\r\n"
                      . "www.koreanluxury.com\r\n";
              break;
            case 2:
              $subject = 'Maaf status GOLD kamu sudah BERAKHIR';
              $email_message = "Hello, karena tidak mencapai target pembelian, maka status GOLD kamu sudah BERAKHIR..\r\n\r\n"
                      . "Status kamu sekarang adalah SILVER..\r\n\r\n"
                      . "Jika kamu tidak melakukan pembelian apapun selama 1 bulan kedepan, maka status SILVER kamu pun akan berakhir..\r\n\r\n"
                      . "Untuk informasi yang berkaitan dengan reseller, silahkan klik: http://www.koreanluxury.com/reseller \r\n\r\n"
                      . "Best Regards,\r\n"
                      . "www.koreanluxury.com\r\n";
              break;
            case 3:
              $subject = 'Maaf status PLATINUM kamu sudah BERAKHIR';
              $email_message = "Hello, karena tidak mencapai target pembelian, maka status PLATINUM kamu sudah BERAKHIR..\r\n\r\n"
                      . "Status kamu sekarang adalah GOLD..\r\n\r\n"
                      . "Jika kamu tidak melakukan pembelian apapun selama 1 bulan kedepan, maka status GOLD kamu pun akan berakhir..\r\n\r\n"
                      . "Untuk informasi yang berkaitan dengan reseller, silahkan klik: http://www.koreanluxury.com/reseller \r\n\r\n"
                      . "Best Regards,\r\n"
                      . "www.koreanluxury.com\r\n";
              break;
            default:
              break;
          }
        }

        if (strlen($subject) > 0) {
          $usersetting->save();

          OrderFunction::sendEmail($email_message, $subject, $user->usersetting->email);
        }
      }
    }

    private function processResellerBU($user) {
        $usersetting = $user->usersetting;

        if($usersetting){
            if($usersetting->status_id < 4){
                UserFunction::upgradeUserStatus();
            }

            if ($user->id != 6830) {
                $usersetting = Usersetting::where('status_id', '>', 1)
                        ->where('status_upgrade_date', '<=', \Carbon\Carbon::now()->subDays(30)->toDateString())
                        ->where('user_id', '=', $user->id)
                        ->first();
                if ($usersetting) {
                    #skip downgrade dulu = 26 July 2017
                    #aktifin tanggal 25 Aug 2017
                    UserFunction::downgradeUserStatus();
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
