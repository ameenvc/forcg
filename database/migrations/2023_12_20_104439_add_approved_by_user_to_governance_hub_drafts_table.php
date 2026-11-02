<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApprovedByUserToGovernanceHubDraftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('governance_hub_drafts', function (Blueprint $table) {
            $table->string('approved_by_user')->after('approved_by')->nullable();
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
            $table->dropColumn('approved_by_user');
        });
    }
}
