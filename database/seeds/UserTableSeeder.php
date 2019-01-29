<?php

use Illuminate\Database\Seeder;
use App\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        
        DB::table('users')->insert([
            'username' => 'root',
            'password' => bcrypt('wuekwtpi'),
            'name' => 'Root',
            'is_admin' => 1,
            'is_owner' => 1,
            'is_marketing' => 1,
            'is_warehouse' => 1,
            'is_finance' => 1
        ]);
        
        DB::table('users')->insert([
            'username' => 'admin',
            'password' => bcrypt('password'),
            'name' => 'Administrator',
            'is_admin' => 1,
            'is_owner' => 1,
            'is_marketing' => 1,
            'is_warehouse' => 1,
            'is_finance' => 1
        ]);
        
        DB::table('users')->insert([
            'username' => 'guest',
            'password' => bcrypt('password'),
            'name' => 'Guest',
            'is_admin' => 0,
            'is_owner' => 0,
            'is_marketing' => 0,
            'is_warehouse' => 0,
            'is_finance' => 0
        ]);
        
        DB::table('users')->insert([
            'username' => 'marketing1',
            'password' => bcrypt('password'),
            'name' => 'Marketing 1',
            'is_admin' => 1,
            'is_owner' => 0,
            'is_marketing' => 1,
            'is_warehouse' => 0,
            'is_finance' => 0
        ]);
        
        DB::table('users')->insert([
            'username' => 'marketing2',
            'password' => bcrypt('password'),
            'name' => 'Marketing 2',
            'is_admin' => 1,
            'is_owner' => 0,
            'is_marketing' => 1,
            'is_warehouse' => 0,
            'is_finance' => 0
        ]);
        
        DB::table('users')->insert([
            'username' => 'marketing3',
            'password' => bcrypt('password'),
            'name' => 'Marketing 3',
            'is_admin' => 1,
            'is_owner' => 0,
            'is_marketing' => 1,
            'is_warehouse' => 0,
            'is_finance' => 0
        ]);
        
        DB::table('users')->insert([
            'username' => 'warehouse1',
            'password' => bcrypt('password'),
            'name' => 'Warehouse 1',
            'is_admin' => 1,
            'is_owner' => 0,
            'is_marketing' => 0,
            'is_warehouse' => 1,
            'is_finance' => 0
        ]);
        
        DB::table('users')->insert([
            'username' => 'warehouse2',
            'password' => bcrypt('password'),
            'name' => 'Warehouse 2',
            'is_admin' => 1,
            'is_owner' => 0,
            'is_marketing' => 0,
            'is_warehouse' => 1,
            'is_finance' => 0
        ]);
        
        DB::table('users')->insert([
            'username' => 'warehouse3',
            'password' => bcrypt('password'),
            'name' => 'Warehouse 3',
            'is_admin' => 1,
            'is_owner' => 0,
            'is_marketing' => 0,
            'is_warehouse' => 1,
            'is_finance' => 0
        ]);
        
        DB::table('users')->insert([
            'username' => 'finance',
            'password' => bcrypt('password'),
            'name' => 'Finance',
            'is_admin' => 1,
            'is_owner' => 0,
            'is_marketing' => 0,
            'is_warehouse' => 1,
            'is_finance' => 1
        ]);
    }
}
