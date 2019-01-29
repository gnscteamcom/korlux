<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFreesamplesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('freesamples', function (Blueprint $table) {
            $table->increments('id');
            
            $table->bigInteger('regular_minimum_nominal')->default(0);
            $table->bigInteger('silver_minimum_nominal')->default(0);
            $table->bigInteger('gold_minimum_nominal')->default(0);
            $table->bigInteger('platinum_minimum_nominal')->default(0);
            $table->tinyInteger('regular_accumulative')->default(0);
            $table->tinyInteger('silver_accumulative')->default(0);
            $table->tinyInteger('gold_accumulative')->default(0);
            $table->tinyInteger('platinum_accumulative')->default(0);
            $table->tinyInteger('active_regular')->default(0);
            $table->tinyInteger('active_silver')->default(0);
            $table->tinyInteger('active_gold')->default(0);
            $table->tinyInteger('active_platinum')->default(0);
            
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
        Schema::drop('freesamples');
    }
}
