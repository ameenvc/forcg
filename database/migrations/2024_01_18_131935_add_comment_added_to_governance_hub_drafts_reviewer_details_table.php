<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommentAddedToGovernanceHubDraftsReviewerDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('governance_hub_drafts_reviewer_details', function (Blueprint $table) {
            $table->boolean('comment_added')->default(false)->after('new_governance_hub_id');
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
            $table->dropColumn('comment_added');
        });
    }
}
