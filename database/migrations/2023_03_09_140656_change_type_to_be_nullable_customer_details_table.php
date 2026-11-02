<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangeTypeToBeNullableCustomerDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_details', function (Blueprint $table) {
            DB::statement("ALTER TABLE `customer_details` CHANGE `type` `type` ENUM('product','service','project') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL;");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_details', function (Blueprint $table) {
            //
        });
    }
}
