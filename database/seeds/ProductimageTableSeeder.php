<?php

use Illuminate\Database\Seeder;
use App\Productimage;

class ProductimageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Productimage::truncate();
        
        DB::table('productimages')->insert([
            'product_id' => 1,
            'image_path' => 'storage\upload\productimages\product1.jpg'
        ]);
        
        DB::table('productimages')->insert([
            'product_id' => 1,
            'image_path' => 'storage\upload\productimages\product2.jpg'
        ]);
        
        DB::table('productimages')->insert([
            'product_id' => 1,
            'image_path' => 'storage\upload\productimages\product3.jpg'
        ]);
        
        DB::table('productimages')->insert([
            'product_id' => 2,
            'image_path' => 'storage\upload\productimages\product2.jpg'
        ]);
        
        DB::table('productimages')->insert([
            'product_id' => 3,
            'image_path' => 'storage\upload\productimages\product3.jpg'
        ]);
        
        DB::table('productimages')->insert([
            'product_id' => 4,
            'image_path' => 'storage\upload\productimages\product4.jpg'
        ]);
        
        DB::table('productimages')->insert([
            'product_id' => 5,
            'image_path' => 'storage\upload\productimages\product5.jpg'
        ]);
        
        DB::table('productimages')->insert([
            'product_id' => 6,
            'image_path' => 'storage\upload\productimages\product6.jpg'
        ]);
        
        DB::table('productimages')->insert([
            'product_id' => 7,
            'image_path' => 'storage\upload\productimages\product7.jpg'
        ]);
        
        DB::table('productimages')->insert([
            'product_id' => 8,
            'image_path' => 'storage\upload\productimages\product8.jpg'
        ]);
        
        DB::table('productimages')->insert([
            'product_id' => 9,
            'image_path' => 'storage\upload\productimages\product9.jpg'
        ]);
        
        
    }
}
