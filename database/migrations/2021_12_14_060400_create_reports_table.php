<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('transaction_code');
            $table->integer('transaction_id');
            $table->integer('customer_id');
            $table->integer('store_id');
            $table->integer('user_id');
            $table->integer('product_id');
            $table->string('date');
            $table->integer('discount');
            $table->integer('total_money');
            $table->integer('origin_price');
            $table->integer('price');
            $table->integer('type');
            $table->integer('stock');
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
        Schema::dropIfExists('reports');
    }
}
