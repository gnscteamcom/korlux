<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderdetailhistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orderdetailhistories', function (Blueprint $table) {
            $table->increments('id');
                
            $table->integer('orderheaderhistory_id');
            $table->integer('product_id');
            $table->integer('qty');
            $table->integer('price');
            $table->integer('weight');
            $table->bigInteger('profit');
            
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
        Schema::drop('orderdetailhistories');
    }
}
