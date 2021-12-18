<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('code');
            $table->integer('customer_id');
            $table->integer('store_id');
            $table->datetime('sell_date');
            $table->string('note');
            $table->tinyInteger('payment_method');
            $table->integer('total');
            $table->integer('total_origin');
            $table->integer('coupon');
            $table->integer('customer_pay');
            $table->integer('total_money');
            $table->integer('quantity');
            $table->integer('lack');
            $table->integer('status');

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
        Schema::dropIfExists('orders');
    }
}
