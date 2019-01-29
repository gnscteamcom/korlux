<?php

use Illuminate\Database\Seeder;
use App\Refundrequest;

class RefundrequestTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Refundrequest::truncate();
    }

}
