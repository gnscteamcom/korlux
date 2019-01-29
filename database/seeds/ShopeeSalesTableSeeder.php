<?php

use Illuminate\Database\Seeder;
use App\Shopeesales;

class ShopeeSalesTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Shopeesales::truncate();
    }

}
