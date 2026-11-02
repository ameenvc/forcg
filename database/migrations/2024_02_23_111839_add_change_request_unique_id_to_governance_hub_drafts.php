<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChangeRequestUniqueIdToGovernanceHubDrafts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('governance_hub_drafts', function (Blueprint $table) {
            $table->string('change_request_unique_id')->nullable()->after('change_request_submitted_by');
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
            $table->dropColumn('change_request_unique_id');
        });
    }
}
