<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('brand_id');
            $table->integer('category_id');
            $table->integer('subcategory_id');
            $table->string('barcode', 32);
            $table->string('product_code', 32);
            $table->text('product_name');
            $table->text('product_desc')->nullable();
            $table->integer('qty');
            $table->integer('reserved_qty')->default(0);
            $table->integer('weight');
            $table->integer('total_buy');
            $table->integer('currentprice_id');
            $table->date('last_price_update')->nullable();
            $table->date('last_stock_update')->nullable();
            $table->tinyInteger('is_set')->default(0);
            
            $table->integer('stock_booked')->default(0);
            $table->integer('stock_sold_30_days')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('products');
    }
}
