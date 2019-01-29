<?php

use Illuminate\Database\Seeder;
use App\Orderdetailhistory;

class OrderdetailhistoryTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Orderdetailhistory::truncate();
    }

}
