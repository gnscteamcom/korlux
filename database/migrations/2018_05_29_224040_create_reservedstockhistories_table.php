<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservedstockhistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservedstockhistories', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('orderheader_id');
            $table->integer('initial_qty');
            $table->integer('current_qty');
            $table->integer('change_qty');
            
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
        Schema::drop('reservedstockhistories');
    }
}
