<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPeriodicalReviewDateToComplianceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('compliance', function (Blueprint $table) {
            $table->timestamp('periodical_review_date')->nullable()->after('freq_review');
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
            $table->dropColumn('periodical_review_date');
        });
    }
}
