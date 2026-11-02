<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProcessAndVendorRequiredInCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->enum('is_process_cat',['yes','no'])->default('no')->after('enable_source');
            $table->enum('is_vendor_cat',['yes','no'])->default('no')->after('enable_source');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('is_process_cat');
            $table->dropColumn('is_vendor_cat');
        });
    }
}
