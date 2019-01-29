<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentconfirmationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paymentconfirmations', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('user_id');
            $table->integer('orderheader_id');
            $table->string('account_name', 64);
            $table->date('payment_date');
            $table->integer('bank_id');
            $table->text('note');
            
            $table->integer('paymentconfirmation_id')->default(0);
            
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
        Schema::drop('paymentconfirmations');
    }
}
