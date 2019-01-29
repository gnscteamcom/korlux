<?php

use Illuminate\Database\Seeder;
use App\Pointconfig;

class PointconfigTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Pointconfig::truncate();

        $data = [
            [
                'is_active' => 0,
            ]
        ];

        Pointconfig::insert($data);
    }

}
