<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnProductSpecificDetailsRequiredInCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->enum('product_specific_details_required',['yes','no'])->default('no')->after('enable_source');
            $table->enum('is_pnc_cat',['yes','no'])->default('no')->after('enable_source');
            $table->dateTime('deleted_at')->nullable()->after('updated_at');
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
            $table->dropColumn('product_specific_details_required');
            $table->dropColumn('is_pnc_cat');
            $table->dropColumn('deleted_at');
        });
    }
}
