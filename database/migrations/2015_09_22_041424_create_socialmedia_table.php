<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialmediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('socialmedias', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('social_id');
            $table->string('social_name', 16);
            $table->string('social_base_link', 32);
            $table->string('social_additional_link', 32);
            $table->string('social_icon', 32);
            
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
        Schema::drop('socialmedias');
    }
}
