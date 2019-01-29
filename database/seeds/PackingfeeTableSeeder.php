<?php

use Illuminate\Database\Seeder;
use App\Packingfee;

class PackingfeeTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Packingfee::truncate();

        $data = [
            'is_active' => 1,
            'minimal_nominal' => 1000000,
            'packing_fee' => 5000
        ];

        Packingfee::insert($data);
    }

}
