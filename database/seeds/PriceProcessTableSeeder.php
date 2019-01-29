<?php

use Illuminate\Database\Seeder;
use App\Priceprocess;

class PriceProcessTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Priceprocess::truncate();
        
        DB::table('priceprocesses')->insert([
            'last_process_date' => '2016-01-01',
        ]);
        
    }
    
}
