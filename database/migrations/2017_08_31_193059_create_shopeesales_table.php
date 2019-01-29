<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopeesalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopeesales', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('orderheader_id');
            $table->integer('customeraddress_id');
            $table->text('shopee_invoice_number');
            $table->text('shopee_resi')->nullable();
            $table->text('username');
            $table->text('shipping_option');
            $table->text('product_list');
            $table->text('send_before');
            
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
        Schema::drop('shopeesales');
    }
}
