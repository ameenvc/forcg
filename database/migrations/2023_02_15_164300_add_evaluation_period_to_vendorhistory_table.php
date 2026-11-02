<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEvaluationPeriodToVendorhistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendorhistory', function (Blueprint $table) {
            $table->text('evaluation_period')->after('evaluation_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendorhistory', function (Blueprint $table) {
            $table->dropColumn('evaluation_period');
        });
    }
}
