<?php

use Illuminate\Database\Seeder;
use App\Price;

class PriceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Price::truncate();
        
        DB::table('prices')->insert([
            'product_id' => 1,
            'regular_price' => 1111,
            'reseller_1' => 1100,
            'reseller_2' => 1000,
            'sale_price' => 999,
            'valid_date' => '2016-01-01'
        ]);
        
        DB::table('prices')->insert([
            'product_id' => 2,
            'regular_price' => 2222,
            'reseller_1' => 2200,
            'reseller_2' => 2000,
            'sale_price' => 1999,
            'valid_date' => '2016-02-02'
        ]);
        
        DB::table('prices')->insert([
            'product_id' => 3,
            'regular_price' => 3333,
            'reseller_1' => 3300,
            'reseller_2' => 3000,
            'sale_price' => 2999,
            'valid_date' => '2016-03-03'
        ]);
        
        DB::table('prices')->insert([
            'product_id' => 4,
            'regular_price' => 4444,
            'reseller_1' => 4400,
            'reseller_2' => 4000,
            'sale_price' => 3999,
            'valid_date' => '2016-04-04'
        ]);
        
    }
    
}
