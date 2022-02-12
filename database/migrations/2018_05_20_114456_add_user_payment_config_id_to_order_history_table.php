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
        Schema::table('order_history', function($table) {
            $table->integer('user_payment_config_id')
                  ->references('id')->on('user_payment_config')
                  ->onDelete('cascade')
                  ->after('cost')
                  ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_history', function($table) {
            $table->dropColumn('user_payment_config_id');
        });
    }
};
