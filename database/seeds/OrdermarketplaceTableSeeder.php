<?php

use Illuminate\Database\Seeder;
use App\Ordermarketplace;

class OrdermarketplaceTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Ordermarketplace::truncate();
    }

}
