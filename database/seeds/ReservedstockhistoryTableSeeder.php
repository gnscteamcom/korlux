<?php

use Illuminate\Database\Seeder;
use App\Reservedstockhistory;

class ReservedstockhistoryTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Reservedstockhistory::truncate();
    }

}
