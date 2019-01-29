<?php

use Illuminate\Database\Seeder;
use App\Usersetting;

class UsersettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Usersetting::truncate();
        
        DB::table('usersettings')->insert([
            'user_id' => 1,
            'first_name' => 'Root',
            'last_name' => '',
            'email' => '',
            'jenis_kelamin' => '',
            'alamat' => '',
            'kecamatan_id' => 0,
            'kodepos' => '',
            'hp' => '',
            'status_id' => 4,
        ]);
        
        DB::table('usersettings')->insert([
            'user_id' => 2,
            'first_name' => 'Admin',
            'last_name' => '',
            'email' => '',
            'jenis_kelamin' => '',
            'alamat' => '',
            'kecamatan_id' => 0,
            'kodepos' => '',
            'hp' => '',
            'status_id' => 3,
        ]);
        
        DB::table('usersettings')->insert([
            'user_id' => 3,
            'first_name' => 'Guest',
            'last_name' => 'Guest',
            'email' => 'test@test.com',
            'jenis_kelamin' => 'Pria',
            'alamat' => 'Jalan Alamat',
            'kecamatan_id' => 4951,
            'kecamatan' => ' Penjaringan, Kota Administrasi Jakarta Utara ',
            'kodepos' => '123456',
            'hp' => '0812345678',
            'status_id' => 1,
        ]);
        
        DB::table('usersettings')->insert([
            'user_id' => 4,
            'first_name' => 'Marketing 1',
            'last_name' => '',
            'email' => '',
            'jenis_kelamin' => '',
            'alamat' => '',
            'kecamatan_id' => 0,
            'kodepos' => '',
            'hp' => '',
            'status_id' => 1,
        ]);
        
        DB::table('usersettings')->insert([
            'user_id' => 5,
            'first_name' => 'Marketing 2',
            'last_name' => '',
            'email' => '',
            'jenis_kelamin' => '',
            'alamat' => '',
            'kecamatan_id' => 0,
            'kodepos' => '',
            'hp' => '',
            'status_id' => 1,
        ]);
        
        DB::table('usersettings')->insert([
            'user_id' => 6,
            'first_name' => 'Marketing 1',
            'last_name' => '',
            'email' => '',
            'jenis_kelamin' => '',
            'alamat' => '',
            'kecamatan_id' => 0,
            'kodepos' => '',
            'hp' => '',
            'status_id' => 1,
        ]);
        
        DB::table('usersettings')->insert([
            'user_id' => 7,
            'first_name' => 'Warehouse 1',
            'last_name' => '',
            'email' => '',
            'jenis_kelamin' => '',
            'alamat' => '',
            'kecamatan_id' => 0,
            'kodepos' => '',
            'hp' => '',
            'status_id' => 1,
        ]);
        
        DB::table('usersettings')->insert([
            'user_id' => 8,
            'first_name' => 'Warehouse 2',
            'last_name' => '',
            'email' => '',
            'jenis_kelamin' => '',
            'alamat' => '',
            'kecamatan_id' => 0,
            'kodepos' => '',
            'hp' => '',
            'status_id' => 1,
        ]);
        
        DB::table('usersettings')->insert([
            'user_id' => 9,
            'first_name' => 'Warehouse 3',
            'last_name' => '',
            'email' => '',
            'jenis_kelamin' => '',
            'alamat' => '',
            'kecamatan_id' => 0,
            'kodepos' => '',
            'hp' => '',
            'status_id' => 1,
        ]);
        
        DB::table('usersettings')->insert([
            'user_id' => 10,
            'first_name' => 'Finance',
            'last_name' => '',
            'email' => '',
            'jenis_kelamin' => '',
            'alamat' => '',
            'kecamatan_id' => 0,
            'kodepos' => '',
            'hp' => '',
            'status_id' => 1,
        ]);
        
    }
}
