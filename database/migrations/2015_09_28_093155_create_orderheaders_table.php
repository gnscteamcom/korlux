<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderheadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orderheaders', function (Blueprint $table) {
            $table->increments('id');
            
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
            
            $table->integer('accept_by')->default(0);
            $table->dateTime('accept_time')->nullable();
            $table->text('cancel_reason')->nullable();
            $table->integer('cancel_by')->default(0);
            $table->text('barcode')->nullable();
            $table->tinyInteger('process_by')->default(0);
            $table->dateTime('process_time')->nullable();
            $table->text('admin_notes')->nullable();
            
            $table->text('resi_otomatis')->nullable();
            
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
        Schema::drop('orderheaders');
    }
}
