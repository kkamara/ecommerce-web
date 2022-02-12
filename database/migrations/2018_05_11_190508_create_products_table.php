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
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')
                  ->references('id')->on('companies')
                  ->onDelete('cascade');
            $table->string('name');
            $table->string('short_description');
            $table->text('long_description')->nullable();
            $table->text('product_details')->nullable();
            $table->string('image_path')->nullable();
            $table->float('cost', 8, 2);
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
};
