<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRiskCategoryColumnToRiskGroupDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('risk_group_details', function (Blueprint $table) {
            $table->string('risk_category')->nullable()->after('group_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('risk_group_details', function (Blueprint $table) {
            $table->dropColumn('risk_category');
        });
    }
}
