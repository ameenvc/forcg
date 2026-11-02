<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnTypeOfJobHistoryComplaintDescriptionAndRecommendedActionOfComplaintTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('complaint', function ($table) {
            $table->longText('job_history')->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->change();
            $table->longText('complaint')->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->change();
            $table->longText('action')->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
