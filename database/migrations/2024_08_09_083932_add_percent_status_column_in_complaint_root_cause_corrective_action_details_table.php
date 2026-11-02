<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPercentStatusColumnInComplaintRootCauseCorrectiveActionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('complaint_root_cause_corrective_action_details', function (Blueprint $table) {
            $table->integer('percent_status')->after('target_date')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('complaint_root_cause_corrective_action_details', function (Blueprint $table) {
            $table->dropColumn('percent_status');
        });
    }
}
