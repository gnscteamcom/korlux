<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStocktransferhistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocktransferhistories', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('product_id');
            $table->integer('initial_qty');
            $table->integer('current_qty');
            $table->integer('transfer_qty');
            $table->integer('initial_reserved_qty');
            $table->integer('current_reserved_qty');
            $table->integer('transfer_reserved_qty');
            
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
        Schema::drop('stocktransferhistories');
    }
}
