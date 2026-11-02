<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewGovernanceHubIdToGovernanceHubDraftsCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('governance_hub_drafts_comments', function (Blueprint $table) {
            $table->unsignedbigInteger('new_governance_hub_id')->after('is_deleted')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('governance_hub_drafts_comments', function (Blueprint $table) {
            $table->dropColumn('new_governance_hub_id');
        });
    }
}
