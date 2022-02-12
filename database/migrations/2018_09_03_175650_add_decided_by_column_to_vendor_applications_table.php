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
        Schema::table('vendor_applications', function (Blueprint $table) {
            $table->integer('decided_by')
                  ->references('id')->on('users')
                  ->onDelete('CASCADE')
                  ->after('accepted')
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
        Schema::table('vendor_applications', function (Blueprint $table) {
            $table->dropColumn('decided_by');
        });
    }
};
