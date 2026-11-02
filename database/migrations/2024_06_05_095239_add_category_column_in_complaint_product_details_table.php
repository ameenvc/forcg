<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryColumnInComplaintProductDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('complaint_product_details', function (Blueprint $table) {
            $table->string('complaint_category')->nullable()->after('cost_impact');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('complaint_product_details', function (Blueprint $table) {
            $table->dropColumn('complaint_category');
        });
    }
}
