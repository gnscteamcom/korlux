<?php

use Illuminate\Database\Seeder;
use App\Bank;

class BankTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bank::truncate();
        
        DB::table('banks')->insert([
            'bank_name' => 'Bank 1',
            'bank_account' => '1234 5678 90',
            'bank_account_name' => 'Team2one'
        ]);
        
        DB::table('banks')->insert([
            'bank_name' => 'Bank 2',
            'bank_account' => '99 999 999 99',
            'bank_account_name' => 'Team2one'
        ]);
        
        DB::table('banks')->insert([
            'bank_name' => 'Bank 3',
            'bank_account' => '8 8888 88888 ',
            'bank_account_name' => 'Team2one'
        ]);
        
    }
}
