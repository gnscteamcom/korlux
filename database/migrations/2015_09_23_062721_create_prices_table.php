<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('product_id');
            $table->bigInteger('regular_price');
            $table->bigInteger('reseller_1');
            $table->bigInteger('reseller_2');
            $table->bigInteger('vvip')->default(0);
            $table->bigInteger('sale_price')->default(0);
            $table->date('valid_date');
            
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
        Schema::drop('prices');
    }
}
