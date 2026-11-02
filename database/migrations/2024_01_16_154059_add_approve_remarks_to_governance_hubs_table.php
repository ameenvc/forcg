<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApproveRemarksToGovernanceHubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('governance_hubs', function (Blueprint $table) {
            $table->longText('approve_remarks')->after('approved_by')->nullable();
			$table->date('approve_remarks_added_date')->after('approve_remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('governance_hubs', function (Blueprint $table) {
            $table->dropColumn('approve_remarks');
			$table->dropColumn('approve_remarks_added_date');
        });
    }
}
