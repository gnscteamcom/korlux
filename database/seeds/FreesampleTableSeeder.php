<?php

use Illuminate\Database\Seeder;
use App\Freesample;

class FreesampleTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Freesample::truncate();
    }

}
