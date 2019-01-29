<?php

use Illuminate\Database\Seeder;
use App\Brand;

class BrandTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Brand::truncate();
        
        DB::table('brands')->insert([
            'brand' => 'Merk 1'
        ]);
        
        DB::table('brands')->insert([
            'brand' => 'Merk 2'
        ]);
        
        DB::table('brands')->insert([
            'brand' => 'Merk 3'
        ]);
        
        DB::table('brands')->insert([
            'brand' => 'Merk 4'
        ]);
        
    }
}
