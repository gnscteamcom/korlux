<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJastipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jastips', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id');
            $table->text('invoicenumber');
            $table->integer('total_weight');
            $table->bigInteger('shipment_cost');
            $table->bigInteger('unique_nominal');
            $table->bigInteger('grand_total');
            $table->bigInteger('total_paid');
            $table->integer('customeraddress_id');
            $table->date('shipment_date')->nullable();
            $table->date('payment_date')->nullable();
            $table->tinyInteger('has_ordered');

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
        Schema::drop('jastips');
    }
}
