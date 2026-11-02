<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDraftReviewedByColumnInComplianceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('compliance', function (Blueprint $table) {
            $table->string('draft_reviewed_by')->after('closedby')->nullable();
            $table->dateTime('draft_reviewed_at')->after('closedby')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('compliance', function (Blueprint $table) {
            $table->dropColumn('draft_reviewed_by');
            $table->dropColumn('draft_reviewed_at');
        });
    }
}
