<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRecordIdToGovernanceHubDraftsCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('governance_hub_drafts_comments', function (Blueprint $table) {
            $table->string('record_id')->after('new_governance_hub_id')->nullable();
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
            $table->dropColumn('record_id');
        });
    }
}
