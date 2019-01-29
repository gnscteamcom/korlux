<?php

use Illuminate\Database\Seeder;
use App\Stockrevise;

class StockreviseTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Stockrevise::truncate();
    }

}
