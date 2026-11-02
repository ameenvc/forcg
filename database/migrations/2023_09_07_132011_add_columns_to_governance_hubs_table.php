<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToGovernanceHubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('governance_hubs', function (Blueprint $table) {
            $table->string('prepared_by')->nullable()->default(null)->before('created_at');
            $table->string('reviewed_by')->nullable()->default(null)->before('created_at');
            $table->string('approved_by')->nullable()->default(null)->before('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('governance_hubs', function (Blueprint $table) {
            $table->dropColumn('prepared_by');
            $table->dropColumn('reviewed_by');
            $table->dropColumn('approved_by');
        });
    }
}
