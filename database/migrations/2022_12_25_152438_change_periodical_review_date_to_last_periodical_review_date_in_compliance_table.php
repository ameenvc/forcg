<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePeriodicalReviewDateToLastPeriodicalReviewDateInComplianceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('compliance', function (Blueprint $table) {
            $table->renameColumn('periodical_review_date', 'last_periodical_review_date');
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
            $table->renameColumn('last_periodical_review_date', 'periodical_review_date');
        });
    }
}
