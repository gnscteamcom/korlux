<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('username')->unique();
            $table->string('password', 60);
            $table->string('name');
            $table->integer('is_admin');
            $table->integer('is_owner');
            $table->integer('is_marketing');
            $table->integer('is_warehouse');
            $table->integer('is_finance');
            $table->integer('is_processed');
            $table->rememberToken();
            
            $table->text('landing_url')->nullable();
            
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
        Schema::drop('users');
    }
}
