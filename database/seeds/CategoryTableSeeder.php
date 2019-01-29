<?php

use Illuminate\Database\Seeder;
use App\Category;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::truncate();
        
        DB::table('categories')->insert([
            'category' => 'Kategori 1',
            'position' => 1
        ]);
        
        DB::table('categories')->insert([
            'category' => 'Kategori 2',
            'position' => 2
        ]);
        
        DB::table('categories')->insert([
            'category' => 'Kategori 3',
            'position' => 3
        ]);
        
        DB::table('categories')->insert([
            'category' => 'Kategori 4',
            'position' => 4
        ]);
        
    }
}
