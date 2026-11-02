<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDraftApproveAcceptRemarkToGovernanceHubDraftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('governance_hub_drafts', function (Blueprint $table) {
             $table->longText('draft_approve_accept_remark')->after('approved_date')->nullable();
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
            $table->dropColumn('draft_approve_accept_remark');
        });
    }
}
