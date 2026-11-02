<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCorrectiveIssueIdToIncidentReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('incident_reports', function (Blueprint $table) {
            $table->string('corrective_issue_id')->nullable()->after('rej_reason');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('incident_reports', function (Blueprint $table) {
            $table->dropColumn('corrective_issue_id');
        });
    }
}
