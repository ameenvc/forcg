<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCorrectionStatusAndAveragePercentColumnsInComplaintTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('complaint', function (Blueprint $table) {
            $table->integer('correction_status')->after('actionstatus')->default(0);
            $table->integer('average_percent')->after('correction_status')->default(0);
            $table->boolean('correction_proposed')->after('rootcause_updated_by_investigation_team')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('complaint', function (Blueprint $table) {
            $table->dropColumn('correction_status');
            $table->dropColumn('average_percent');
            $table->dropColumn('correction_proposed');
        });
    }
}
