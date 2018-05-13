<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
            $table->string('slug');
            $table->string('name');
            $table->string('image_path')->nullable();
            $table->integer('cost');
            $table->boolean('shippable')->default(0);
            $table->boolean('free_delivery')->default(0);
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
        Schema::dropIfExists('products');
    }
}
