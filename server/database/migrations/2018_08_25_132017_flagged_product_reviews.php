<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FlaggedProductReviews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flagged_product_reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_reviews_id')
                  ->references('id')->on('product_reviews')
                  ->onDelete('cascade');
            $table->string('flagged_from_ip');
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
        Schema::dropIfExists('flagged_product_reviews');
    }
}
