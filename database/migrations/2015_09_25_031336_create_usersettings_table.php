<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usersettings', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('user_id');
            $table->text('fb_id')->nullable();
            $table->text('first_name');
            $table->string('last_name', 32);
            $table->string('email', 48);
            $table->string('jenis_kelamin', 8);
            $table->text('alamat');
            $table->integer('kecamatan_id')->default(0);
            $table->text('kecamatan')->nullable();
            $table->string('kodepos', 6);
            $table->text('hp');
            $table->integer('status_id');
            $table->date('status_upgrade_date')->nullable();
            
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
        Schema::drop('usersettings');
    }
}
