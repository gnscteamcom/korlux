<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderheaderhistoriesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('orderheaderhistories', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('orderheader_id');
            $table->integer('user_id');
            $table->string('invoicenumber', 12);
            $table->string('shipment_invoice', 32)->nullable();
            $table->integer('total_weight');
            $table->bigInteger('shipment_cost');
            $table->bigInteger('discount_coupon');
            $table->bigInteger('discount_point');
            $table->bigInteger('unique_nominal');
            $table->bigInteger('insurance_fee');
            $table->bigInteger('packing_fee');
            $table->bigInteger('grand_total');
            $table->bigInteger('total_paid');
            $table->text('shipment_method');
            $table->integer('shipmethod_id')->default(0);
            $table->integer('customeraddress_id');
            $table->integer('dropship_id')->nullable();
            $table->integer('status_id');
            $table->text('note')->nullable();
            $table->date('shipment_date')->nullable();
            $table->date('payment_date')->nullable();
            $table->tinyInteger('is_print');
            $table->tinyInteger('is_process');
            $table->integer('discountcoupon_id');
            $table->integer('freesample_qty')->default(0);
            
            $table->text('payment_link')->nullable();
            
            $table->integer('edited_by');
            $table->text('edited_name');

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
        Schema::drop('orderheaderhistories');
    }

}
