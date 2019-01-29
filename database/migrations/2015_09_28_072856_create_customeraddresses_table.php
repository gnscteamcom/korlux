<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomeraddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customeraddresses', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('user_id');
            $table->string('address_name', 64);
            $table->text('first_name');
            $table->string('last_name', 32);
            $table->text('alamat');
            $table->integer('kecamatan_id')->default(0);
            $table->text('kecamatan')->nullable();
            $table->string('provinsi', 48);
            $table->string('kodepos', 6);
            $table->text('hp');
            
            
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
        Schema::drop('customeraddresses');
    }
}
