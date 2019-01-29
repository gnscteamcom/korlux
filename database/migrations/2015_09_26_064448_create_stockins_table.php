<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stockins', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('product_id');
            $table->integer('qty');
            $table->integer('remaining_qty');
            $table->integer('reserved_qty')->default(0);
            
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
        Schema::drop('stockins');
    }
}
