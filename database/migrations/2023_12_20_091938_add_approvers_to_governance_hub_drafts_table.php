<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApproversToGovernanceHubDraftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('governance_hub_drafts', function (Blueprint $table) {
			$table->string('approvers')->after('reviewer')->nullable();
			$table->string('reviewed_by',20)->after('approvers')->nullable();
			$table->string('approved_by',20)->after('reviewed_by')->nullable();
			$table->date('approved_date')->after('approved_by')->nullable();
			$table->string('procedure_type',20)->after('approved_date')->nullable();
			$table->longText('change_reason')->after('procedure_type')->nullable();
			$table->longText('proposed_changes')->after('change_reason')->nullable();
			$table->longText('document_effects')->after('proposed_changes')->nullable();
			$table->string('priority_level',50)->after('document_effects')->nullable();
			$table->string('revision')->after('priority_level')->nullable();
			$table->string('document_number')->after('revision')->nullable();
			$table->unsignedbigInteger('governance_hub_id')->after('document_number')->nullable();
			$table->date('change_request_submitted_date')->after('governance_hub_id')->nullable();
			$table->string('change_request_submitted_by')->after('change_request_submitted_date')->nullable();          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('governance_hub_drafts', function (Blueprint $table) {
            $table->dropColumn('approvers');
			$table->dropColumn('reviewed_by');
			$table->dropColumn('approved_by');
			$table->dropColumn('approved_date');
			$table->dropColumn('procedure_type');
			$table->dropColumn('change_reason');
			$table->dropColumn('proposed_changes');
			$table->dropColumn('document_effects');
			$table->dropColumn('priority_level');
			$table->dropColumn('revision');
			$table->dropColumn('document_number');
			$table->dropColumn('governance_hub_id');
			$table->dropColumn('change_request_submitted_date');
			$table->dropColumn('change_request_submitted_by');
        });
    }
}
