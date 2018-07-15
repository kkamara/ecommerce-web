<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteOrderHistoryTableProductIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_history', function($table) {
            $table->dropColumn('product_id');
        });
    }

    public function down()
    {
        Schema::table('order_history', function($table) {
            $table->integer('product_id')
                  ->references('id')->on('products')
                  ->onDelete('cascade');
        });
    }
}
