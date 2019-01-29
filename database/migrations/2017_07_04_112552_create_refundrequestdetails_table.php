<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefundrequestdetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refundrequestdetails', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('refund_id');
            $table->integer('orderdetail_id');
            $table->integer('initial_qty');
            $table->integer('current_qty');
            $table->integer('refund_qty');
            $table->integer('price');
            $table->integer('total_refund');
            
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
        Schema::drop('refundrequestdetails');
    }
}
