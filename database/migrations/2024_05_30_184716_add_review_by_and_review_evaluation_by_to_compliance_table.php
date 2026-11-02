<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReviewByAndReviewEvaluationByToComplianceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('compliance', function (Blueprint $table) {
            $table->string('reviewed_by')->nullable()->after('evaluation_date');
            $table->string('reviewed_evaluation_by')->nullable()->after('review_evaluation_date');
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
            $table->dropColumn(['reviewed_by', 'reviewed_evaluation_by']);
        });
    }
}
