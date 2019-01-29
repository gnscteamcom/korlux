<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePointhistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pointhistories', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('user_id');
            $table->bigInteger('point_added');
            $table->bigInteger('point_used');
            $table->integer('orderheader_id');
            $table->date('available_date')->nullable();
            $table->tinyInteger('isCalculate');
            
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
        Schema::drop('pointhistories');
    }
}
