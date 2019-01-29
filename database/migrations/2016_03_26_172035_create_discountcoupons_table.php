<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountcouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discountcoupons', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('coupon_code', 32);
            $table->date('valid_date');
            $table->date('expired_date');
            $table->integer('available_count');
            $table->tinyInteger('available_for_status')->default(1);
            $table->bigInteger('nominal_discount');
            $table->tinyInteger('percentage_discount')->unsigned();
            $table->integer('only_for_user')->nullable();
            
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
        Schema::drop('discountcoupons');
    }
}
