<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReviewRejectedByUserToGovernanceHubDrafts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('governance_hub_drafts', function (Blueprint $table) {
            $table->unsignedBigInteger('review_rejected_by_user')->nullable()->after('change_request_unique_id');
			$table->unsignedBigInteger('approve_rejected_by_user')->nullable()->after('review_rejected_by_user');
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
            $table->dropColumn('review_rejected_by_user');
			$table->dropColumn('approve_rejected_by_user');
        });
    }
}
