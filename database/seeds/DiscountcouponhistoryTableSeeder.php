<?php

use Illuminate\Database\Seeder;
use App\Discountcouponhistory;

class DiscountcouponhistoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Discountcouponhistory::truncate();
    }
}
