<?php

use Illuminate\Database\Seeder;
use App\Subcategory;

class SubcategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Subcategory::truncate();
        
        DB::table('subcategories')->insert([
            'category_id' => 1,
            'subcategory' => 'Subkategori 1',
            'position' => 1
        ]);
        
        DB::table('subcategories')->insert([
            'category_id' => 1,
            'subcategory' => 'Subkategori 2',
            'position' => 2
        ]);
        
        DB::table('subcategories')->insert([
            'category_id' => 2,
            'subcategory' => 'Subkategori 3',
            'position' => 3
        ]);
        
        DB::table('subcategories')->insert([
            'category_id' => 2,
            'subcategory' => 'Subkategori 4',
            'position' => 4
        ]);
        
        DB::table('subcategories')->insert([
            'category_id' => 3,
            'subcategory' => 'Subkategori 5',
            'position' => 5
        ]);
        
    }
}
