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
        Schema::create('user_payment_addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_payment_config_id')
                  ->references('id')->on('user_payment_config')
                  ->onDelete('cascade');
            $table->string('mobile_number')->nullable();
            $table->string('mobile_number_extension')->nullable();
            $table->string('phone_number');
            $table->string('phone_number_extension')->nullable();
            $table->string('building_name');
            $table->string('street_address1');
            $table->string('street_address2')->nullable();
            $table->string('street_address3')->nullable();
            $table->string('street_address4')->nullable();
            $table->string('county')->nullable();
            $table->string('city');
            $table->string('country');
            $table->string('postcode');
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
        Schema::dropIfExists('user_payment_addresses');
    }
};
