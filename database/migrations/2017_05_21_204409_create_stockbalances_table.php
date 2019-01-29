<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockbalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stockbalances', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('product_id');
            $table->integer('current_stock');
            $table->integer('stock_in');
            $table->integer('stock_booked');
            $table->integer('stock_out');
            $table->integer('stock_total');
            $table->integer('stock_system');
            $table->text('notes');
            
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
        Schema::drop('stockbalances');
    }
}
