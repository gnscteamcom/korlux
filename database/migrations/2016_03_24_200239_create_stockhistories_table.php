<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockhistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stockhistories', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('product_id');
            $table->integer('initial_qty');
            $table->integer('change_qty');
            $table->bigInteger('initial_capital');
            $table->bigInteger('change_capital');
            $table->integer('user_id');
            
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
        Schema::drop('stockhistories');
    }
}
