<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubmenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submenus', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('menu_id');
            $table->text('submenu');
            $table->text('submenu_link');
            $table->text('submenu_icon')->nullable();
            $table->tinyInteger('position');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('submenus');
    }
}
