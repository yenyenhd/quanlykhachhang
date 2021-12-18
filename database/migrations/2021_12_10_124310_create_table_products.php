<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->Increments('id');
            $table->integer('category_id')->index();
            $table->integer('user_id')->index();
            $table->string('name');
            $table->string('slug');
            $table->string('description');
            $table->string('avatar_path');
            $table->float('origin_price');
            $table->float('sell_price');
            $table->integer('quantity');
            $table->integer('sold')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('hot')->default(0);
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
        Schema::dropIfExists('products');
    }
}
