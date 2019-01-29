<?php

use Illuminate\Database\Seeder;
use App\Orderheaderhistory;

class OrderheaderhistoryTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Orderheaderhistory::truncate();
    }

}
