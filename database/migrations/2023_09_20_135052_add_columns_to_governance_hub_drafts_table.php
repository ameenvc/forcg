<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToGovernanceHubDraftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('governance_hub_drafts', function (Blueprint $table) {
            $table->string('reviewer')->nullable()->default(null)->after('status');
            $table->text('reviewed_rejection_reason')->nullable()->default(null)->after('status');
            $table->text('approve_rejection_reason')->nullable()->default(null)->after('status');
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
            $table->dropColumn('reviewer');
            $table->dropColumn('reviewed_rejection_reason');
            $table->dropColumn('approve_rejection_reason');
        });
    }
}
