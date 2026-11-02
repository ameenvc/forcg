<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInCorrectiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('corrective', function (Blueprint $table) {
            $table->boolean('is_reopened')->default(false)->after('is_req_rc_analysis_management_review');
            $table->boolean('is_responsible_person_changed')->default(false)->after('is_req_rc_analysis_management_review');
            $table->boolean('is_team_changed')->default(false)->after('is_req_rc_analysis_management_review');
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
            $table->dropColumn('is_reopened');
            $table->dropColumn('is_responsible_person_changed');
            $table->dropColumn('is_team_changed');
        });
    }
}
