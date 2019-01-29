<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefundrequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refundrequests', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('order_id');
            $table->integer('status_id')->default(1);
            $table->text('refund_reason')->nullable();
            
            $table->text('approve_reason')->nullable();
            $table->text('reject_reason')->nullable();
            
            $table->bigInteger('total_refund')->default(0);
            $table->tinyInteger('is_full_refund')->default(1);
            $table->tinyInteger('is_refund_voucher')->default(0);
            $table->text('voucher_code')->nullable();
            
            $table->text('bank_name')->nullable();
            $table->text('account_name')->nullable();
            $table->text('account_number')->nullable();
            
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
        Schema::drop('refundrequests');
    }
}
