<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_history_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_history_id')
                  ->references('id')->on('order_history')
                  ->onDelete('cascade');
            $table->integer('product_id')
                  ->references('id')->on('products')
                  ->onDelete('cascade');
            $table->float('cost', 8, 2);
            $table->boolean('shippable')->default(0);
            $table->boolean('free_delivery')->default(0);
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
        Schema::dropIfExists('order_history_products');
    }
};
