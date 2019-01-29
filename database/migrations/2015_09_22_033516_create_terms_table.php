<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terms', function (Blueprint $table) {
            $table->increments('id');
                
            $table->text('pricing_policy')->nullable();
            $table->text('payment')->nullable();
            $table->text('order')->nullable();
            $table->text('payment_confirmation')->nullable();
            $table->text('shipment')->nullable();
            $table->text('return')->nullable();
            $table->text('faq')->nullable();
            $table->text('howtobuy')->nullable();
            $table->text('reseller')->nullable();
            
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
        Schema::drop('terms');
    }
}
