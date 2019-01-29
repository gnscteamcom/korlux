<?php

use Illuminate\Database\Seeder;
use App\Stockbalance;

class StockbalanceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Stockbalance::truncate();
    }
}
