<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsermenusTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('usermenus', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id');
            $table->integer('menu_id');
            $table->integer('submenu_id');
            $table->tinyInteger('is_active');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('usermenus');
    }

}
