<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password');
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
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
