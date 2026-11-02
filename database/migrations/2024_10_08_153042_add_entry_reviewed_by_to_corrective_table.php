<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEntryReviewedByToCorrectiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('corrective', function (Blueprint $table) {
            $table->bigInteger('entry_reviewed_by')->nullable()->after('emergency_drill_report_id');
			$table->date('entry_reviewed_by_date')->nullable()->after('entry_reviewed_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('corrective', function (Blueprint $table) {
             $table->dropColumn('entry_reviewed_by');
             $table->dropColumn('entry_reviewed_by_date');
        });
    }
}
