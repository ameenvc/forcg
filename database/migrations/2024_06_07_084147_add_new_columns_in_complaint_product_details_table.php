<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsInComplaintProductDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('complaint_product_details', function (Blueprint $table) {
            $table->text('complaint_category')->change();
            $table->text('sale_return_request')->nullable()->after('complaint_category');
            $table->text('rework_request')->nullable()->after('sale_return_request');

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
            $table->string('complaint_category')->change();
            $table->dropColumn('sale_return_request');
            $table->dropColumn('rework_request');

        });
    }
}
