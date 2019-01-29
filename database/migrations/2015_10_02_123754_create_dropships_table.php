<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDropshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dropships', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('user_id');
            $table->string('dropship_name', 64);
            $table->string('name', 32);
            $table->string('hp', 16);
            
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
        Schema::drop('dropships');
    }
}
