<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountcouponhistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discountcouponhistories', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('discountcoupon_id');
            $table->integer('user_id');
            $table->integer('initial_available_count');
            $table->integer('change_available_count');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('discountcouponhistories');
    }
}
