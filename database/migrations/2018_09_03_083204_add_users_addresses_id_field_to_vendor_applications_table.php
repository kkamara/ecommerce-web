<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsersAddressesIdFieldToVendorApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_applications', function (Blueprint $table) {
            $table->integer('users_addresses_id')
                  ->references('id')->on('users_addresses')
                  ->onDelete('cascade')
                  ->unique()
                  ->after('proposed_company_name');
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
            $table->dropColumn('users_addresses_id');
        });
    }
}
