<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReviewedByAndReviewedDateColumnToMitigationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mitigations', function (Blueprint $table) {
            $table->unsignedBigInteger('reviewed_by')->nullable()->after('updated_by');
            $table->timestamp('reviewed_date')->nullable()->after('reviewed_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mitigations', function (Blueprint $table) {
            $table->dropColumn(['reviewed_by', 'reviewed_date']);
        });
    }
}
