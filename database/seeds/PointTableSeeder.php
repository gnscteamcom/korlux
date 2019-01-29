<?php

use Illuminate\Database\Seeder;
use App\Point;

class PointTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Point::truncate();
        
        DB::table('points')->insert([
            'minimal_amount' => 1,
            'maximal_amount' => 100000,
            'point_percentage' => 1
        ]);
        
        DB::table('points')->insert([
            'minimal_amount' => 100001,
            'maximal_amount' => 250000,
            'point_percentage' => 2
        ]);
        
        DB::table('points')->insert([
            'minimal_amount' => 250001,
            'maximal_amount' => 500000,
            'point_percentage' => 3
        ]);
        
        DB::table('points')->insert([
            'minimal_amount' => 500001,
            'maximal_amount' => 1000000,
            'point_percentage' => 4
        ]);
        
        DB::table('points')->insert([
            'minimal_amount' => 1000001,
            'maximal_amount' => 9999999999,
            'point_percentage' => 5
        ]);
        
        
    }
}
