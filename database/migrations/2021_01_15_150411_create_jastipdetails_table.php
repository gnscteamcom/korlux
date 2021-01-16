<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJastipdetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jastipdetails', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('jastip_id');
            $table->integer('qty');
            $table->integer('price');
            $table->integer('weight');

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
        Schema::drop('jastipdetails');
    }
}
