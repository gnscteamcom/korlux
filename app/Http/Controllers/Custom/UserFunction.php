<?php

namespace App\Http\Controllers\Custom;
use App\User;
use App\Usermenu;
use App\Orderheader;

class UserFunction {

    public static function grabUserData($user_id){

        $user = User::join('usersettings', 'usersettings.user_id', '=', 'users.id')
                ->where('users.id', '=', $user_id)
                ->select('users.username', 'usersettings.first_name', 'usersettings.last_name',
                        'usersettings.email', 'usersettings.jenis_kelamin', 'usersettings.alamat',
                        'usersettings.kecamatan_Id', 'usersettings.kecamatan', 'usersettings.kodepos',
                        'usersettings.hp')
                ->first();

        return $user;

    }


    public static function upgradeUserStatus(){

        $user = auth()->user();
        $day_limit = 30;

        $grand_total = UserFunction::getShopValue($day_limit, 0, $user->id);

        //proses upgrade status
        $subject = "";
        $email_message = "";
        $usersetting = $user->usersetting;
        if($grand_total >= 60000000 && $usersetting->status_id < 4){
            $usersetting->status_id = 4;
            $usersetting->status_upgrade_date = \Carbon\Carbon::now()->toDateString();
            $subject = 'Selamat!! kamu sudah menjadi PLATINUM Reseller';
            $email_message = "SELAMAT!!!!\r\n\r\n"
                    . "Karena sudah mencapai target pembelian, Anda telah menjadi PLATINUM reseller. Harga yang tertera di http://www.koreanluxury.com akan lebih murah dari harga sebelumnya..\r\n\r\n"
                    . "PLATINUM RESELLER adalah tingkat reseller teringgi. Untuk informasi yang berkaitan dengan reseller, silahkan klik: http://www.koreanluxury.com/reseller \r\n\r\n"
                    . "Jika BELUM PERNAH melakukan pendaftaran reseller via email, mohon segera lakukan pendaftaran untuk mendapatkan pricelist reseller, dan info-info lainnya. Kirim email ke: koreanluxuryshop@gmail.com \r\n\r\n"
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
        }
        else if($grand_total >= 15000000 && $usersetting->status_id < 3){
            $usersetting->status_id = 3;
            $usersetting->status_upgrade_date = \Carbon\Carbon::now()->toDateString();
            $subject = 'Selamat!! kamu sudah menjadi GOLD Reseller';
            $email_message = "SELAMAT!!!!\r\n\r\n"
                    . "Karena sudah mencapai target pembelian, Anda telah menjadi GOLD reseller. Harga yang tertera di http://www.koreanluxury.com akan lebih murah dari harga sebelumnya..\r\n\r\n"
                    . "Terus tingkatkan pembelian anda untuk menjadi PLATINUM RESELLER. Untuk informasi yang berkaitan dengan reseller, silahkan klik: http://www.koreanluxury.com/reseller \r\n\r\n"
                    . "Jika BELUM PERNAH melakukan pendaftaran reseller via email, mohon segera lakukan pendaftaran untuk mendapatkan pricelist reseller, dan info-info lainnya. Kirim email ke: koreanluxuryshop@gmail.com \r\n\r\n"
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
        }
        else if($grand_total >= 3000000 && $usersetting->status_id < 2){
            $usersetting->status_id = 2;
            $usersetting->status_upgrade_date = \Carbon\Carbon::now()->toDateString();
            $subject = 'Selamat!! kamu sudah menjadi SILVER Reseller';
            $email_message = "SELAMAT!!!!\r\n\r\n"
                    . "Karena sudah mencapai target pembelian, Anda telah menjadi SILVER reseller. Harga yang tertera di http://www.koreanluxury.com akan lebih murah dari harga sebelumnya..\r\n\r\n"
                    . "Terus tingkatkan pembelian anda untuk menjadi GOLD RESELLER. Untuk informasi yang berkaitan dengan reseller, silahkan klik: http://www.koreanluxury.com/reseller \r\n\r\n"
                    . "Jika BELUM PERNAH melakukan pendaftaran reseller via email, mohon segera lakukan pendaftaran untuk mendapatkan pricelist reseller, dan info-info lainnya. Kirim email ke: koreanluxuryshop@gmail.com \r\n\r\n"
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
        }

        if(strlen($subject) > 0){
            $usersetting->save();
            UserFunction::processOrderheader($day_limit, $user->id);
            OrderFunction::sendEmail($email_message, $subject, $user->usersetting->email);
        }

    }


    public static function downgradeUserStatus() {

        $user = auth()->user();
        $usersetting = $user->usersetting;

        $day_limit = 30;
        $grand_total = UserFunction::getShopValue($day_limit, false, $user->id);

        //downgrade user
        $subject = "";
        $email_message = "";
        if ($grand_total <= 0 && $usersetting->status_id > 1) {
            $usersetting->status_id = 1;
            $subject = 'Maaf status SILVER kamu sudah BERAKHIR';
            $email_message = "Hello, karena sudah 1 bulan kamu tidak melakukan order maka status SILVER kamu sudah BERAKHIR..\r\n"
                    . "Sekarang harga yang tertera di www.koreanluxury.com adalah harga NORMAL..\r\n"
                    . "Untuk informasi yang berkaitan dengan reseller, silahkan klik: http://www.koreanluxury.com/reseller \r\n\r\n"
                    . "Best Regards,\r\n"
                    . "www.koreanluxury.com\r\n";
        } else if ($grand_total < 8000000 && $usersetting->status_id > 2) {
            $usersetting->status_id = 2;
            $subject = 'Maaf status GOLD kamu sudah BERAKHIR';
            $email_message = "Hello, karena tidak mencapai target pembelian, maka status GOLD kamu sudah BERAKHIR..\r\n\r\n"
                    . "Status kamu sekarang adalah SILVER..\r\n\r\n"
                    . "Jika kamu tidak melakukan pembelian apapun selama 1 bulan kedepan, maka status SILVER kamu pun akan berakhir..\r\n\r\n"
                    . "Untuk informasi yang berkaitan dengan reseller, silahkan klik: http://www.koreanluxury.com/reseller \r\n\r\n"
                    . "Best Regards,\r\n"
                    . "www.koreanluxury.com\r\n";
        } else if ($grand_total < 40000000 && $usersetting->status_id > 3) {
            $usersetting->status_id = 3;
            $subject = 'Maaf status PLATINUM kamu sudah BERAKHIR';
            $email_message = "Hello, karena tidak mencapai target pembelian, maka status PLATINUM kamu sudah BERAKHIR..\r\n\r\n"
                    . "Status kamu sekarang adalah GOLD..\r\n\r\n"
                    . "Jika kamu tidak melakukan pembelian apapun selama 1 bulan kedepan, maka status GOLD kamu pun akan berakhir..\r\n\r\n"
                    . "Untuk informasi yang berkaitan dengan reseller, silahkan klik: http://www.koreanluxury.com/reseller \r\n\r\n"
                    . "Best Regards,\r\n"
                    . "www.koreanluxury.com\r\n";
        }

        if (strlen($subject) > 0) {
            $usersetting->save();
            OrderFunction::sendEmail($email_message, $subject, $user->usersetting->email);
        }
    }

    public static function getShopValue($sub_days, $isProcess, $user_id){

        //siapin range tanggalnya
        $last_month = \Carbon\Carbon::now()->subDays($sub_days)->toDateTimeString();
        $now = \Carbon\Carbon::now()->toDateTimeString();

        $orderheaders = Orderheader::where('user_id', '=', $user_id)
                ->whereBetween('status_id', [14,15])
                ->where('created_at', '>=', $last_month)
                ->where('created_at', '<', $now);

        //kalau dia true, artinya ini mau upgrade
        if($isProcess){
            $orderheaders = $orderheaders->where('is_process', '=', 0);
        }

        $grand_total = $orderheaders->sum('grand_total');

        return $grand_total;

    }


    private static function processOrderheader($sub_days, $user_id){

        $last_month = \Carbon\Carbon::now()->subDays($sub_days)->toDateString();
        $now = \Carbon\Carbon::now()->toDateString();

        $orderheaders = Orderheader::where('user_id', '=', $user_id)
                ->whereBetween('status_id', [14,15])
                ->where('created_at', '>=', $last_month)
                ->where('created_at', '<', $now)
                ->select('id', 'is_process')
                ->get();

        foreach($orderheaders as $orderheader){
            $orderheader->is_process = 1;
            $orderheader->save();
        }

    }

}
