<?php

use Illuminate\Database\Seeder;
use App\Product;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::truncate();
        
        DB::table('products')->insert([
            'brand_id' => 1,
            'category_id' => 1,
            'subcategory_id' => 1,
            'barcode' => 11,
            'product_code' => 'product_1',
            'product_name' => 'Product 1',
            'product_desc' => 'Desc 1',
            'qty' => 10,
            'weight' => 10,
            'is_set' => 1
        ]);
        
        DB::table('products')->insert([
            'brand_id' => 1,
            'category_id' => 2,
            'subcategory_id' => 1,
            'barcode' => 12,
            'product_code' => 'product_2',
            'product_name' => 'Product 2',
            'product_desc' => 'Desc 2',
            'qty' => 20,
            'weight' => 20,
        ]);
        
        DB::table('products')->insert([
            'brand_id' => 1,
            'category_id' => 3,
            'subcategory_id' => 2,
            'barcode' => 13,
            'product_code' => 'product_3',
            'product_name' => 'Product 3',
            'product_desc' => 'Desc 3',
            'qty' => 20,
            'weight' => 30,
        ]);
        
        DB::table('products')->insert([
            'brand_id' => 2,
            'category_id' => 1,
            'subcategory_id' => 3,
            'barcode' => 21,
            'product_code' => 'product_4',
            'product_name' => 'Product 4',
            'product_desc' => 'Desc 4',
            'qty' => 40,
            'weight' => 40,
            'is_set' => 1
        ]);
        
        DB::table('products')->insert([
            'brand_id' => 2,
            'category_id' => 2,
            'barcode' => 22,
            'product_code' => 'product_5',
            'product_name' => 'Product 5',
            'product_desc' => 'Desc 5',
            'qty' => 50,
            'weight' => 50,
        ]);
        
        DB::table('products')->insert([
            'brand_id' => 2,
            'category_id' => 3,
            'barcode' => 23,
            'product_code' => 'product_6',
            'product_name' => 'Product 6',
            'product_desc' => 'Desc 6',
            'qty' => 60,
            'weight' => 60,
        ]);
        
        DB::table('products')->insert([
            'brand_id' => 3,
            'category_id' => 1,
            'barcode' => 31,
            'product_code' => 'product_7',
            'product_name' => 'Product 7',
            'product_desc' => 'Desc 7',
            'qty' => 70,
            'weight' => 70,
        ]);
        
        DB::table('products')->insert([
            'brand_id' => 3,
            'category_id' => 2,
            'barcode' => 32,
            'product_code' => 'product_8',
            'product_name' => 'Product 8',
            'product_desc' => 'Desc 8',
            'qty' => 80,
            'weight' => 80,
        ]);
        
        DB::table('products')->insert([
            'brand_id' => 3,
            'category_id' => 3,
            'barcode' => 33,
            'product_code' => 'product_9',
            'product_name' => 'Product 9',
            'product_desc' => 'Desc 9',
            'qty' => 90,
            'weight' => 90,
        ]);
        
    }
}
