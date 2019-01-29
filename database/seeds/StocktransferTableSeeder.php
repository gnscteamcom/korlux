<?php

use Illuminate\Database\Seeder;
use App\Stocktransferhistory;

class StocktransferTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Stocktransferhistory::truncate();
    }

}
