<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReviewedByDateToIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('issues', function (Blueprint $table) {
            $table->date('reviewed_by_date')->after('reviewedby')->nullable();
			$table->date('hod_reviewed_by_date')->after('hod_user')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('issues', function (Blueprint $table) {
            $table->dropColumn('reviewed_by_date');
			$table->dropColumn('hod_reviewed_by_date');
        });
    }
}
