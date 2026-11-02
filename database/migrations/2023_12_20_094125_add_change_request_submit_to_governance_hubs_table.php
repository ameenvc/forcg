<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChangeRequestSubmitToGovernanceHubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('governance_hubs', function (Blueprint $table) {
            $table->boolean('change_request_submit')->default(false)->after('approved_by');
			$table->date('change_request_submitted_date')->after('change_request_submit')->nullable();
			$table->string('change_request_submitted_by')->after('change_request_submitted_date')->nullable();
			$table->date('change_request_cancelled_date')->after('change_request_submitted_by')->nullable();
			$table->string('change_request_cancelled_by')->after('change_request_cancelled_date')->nullable();
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
            $table->dropColumn('change_request_submit');
			$table->dropColumn('change_request_submitted_date');
			$table->dropColumn('change_request_submitted_by');
			$table->dropColumn('change_request_cancelled_date');
			$table->dropColumn('change_request_cancelled_by');
        });
    }
}
