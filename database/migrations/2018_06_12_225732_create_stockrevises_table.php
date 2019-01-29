<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockrevisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stockrevises', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('user_id');
            $table->integer('product_id');
            $table->integer('initial_qty');
            $table->integer('change_qty');
            $table->integer('current_qty');
            $table->tinyInteger('is_approved')->default(0);
            $table->tinyInteger('is_rejected')->default(0);
            $table->dateTime('approve_time')->nullable();
            $table->dateTime('reject_time')->nullable();
            $table->integer('approve_by')->nullable();
            $table->integer('reject_by')->nullable();
            $table->text('reason')->nullable();
            $table->text('notes');
            
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
        Schema::drop('stockrevises');
    }
}
