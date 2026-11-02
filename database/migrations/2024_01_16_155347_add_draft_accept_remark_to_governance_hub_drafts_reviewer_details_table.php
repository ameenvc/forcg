<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDraftAcceptRemarkToGovernanceHubDraftsReviewerDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('governance_hub_drafts_reviewer_details', function (Blueprint $table) {
            $table->longText('draft_accept_remark')->after('draft_reject_reason')->nullable();
			$table->date('draft_accept_remark_date')->after('draft_accept_remark')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('governance_hub_drafts_reviewer_details', function (Blueprint $table) {
            $table->dropColumn('draft_accept_remark');
			$table->dropColumn('draft_accept_remark_date');
        });
    }
}
