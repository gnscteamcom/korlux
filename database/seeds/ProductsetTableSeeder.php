<?php

use Illuminate\Database\Seeder;
use App\Productset;

class ProductsetTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Productset::truncate();
        
        $data = [
            [
                'set_id' => 1,
                'product_id' => 2
            ],
            [
                'set_id' => 1,
                'product_id' => 3
            ],
            [
                'set_id' => 4,
                'product_id' => 5
            ],
            [
                'set_id' => 4,
                'product_id' => 6
            ],
        ];
        
        Productset::insert($data);
    }
}
