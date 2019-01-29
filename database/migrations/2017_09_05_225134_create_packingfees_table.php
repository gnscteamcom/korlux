<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackingfeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packingfees', function (Blueprint $table) {
            $table->increments('id');
            
            $table->tinyInteger('is_active');
            $table->bigInteger('minimal_nominal');
            $table->bigInteger('packing_fee');
            
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
        Schema::drop('packingfees');
    }
}
