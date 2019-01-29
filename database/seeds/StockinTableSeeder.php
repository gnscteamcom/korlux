<?php

use Illuminate\Database\Seeder;
use App\Stockin;

class StockinTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Stockin::truncate();
        
    }
}
