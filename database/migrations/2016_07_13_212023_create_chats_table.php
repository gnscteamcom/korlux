<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('conversation_id');
            $table->string('sender_name');
            $table->tinyInteger('is_new_user');
            $table->tinyInteger('is_new_owner');
            $table->text('chat');
            $table->string('filepath', 100);
            $table->tinyInteger('is_owner');
            
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
        Schema::drop('chats');
    }
}
