<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToManagementReviewSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('management_review_schedules', function (Blueprint $table) {
            $table->text('meeting_cancel_reason')->after('added_by')->nullable();
			$table->string('status',220)->after('meeting_cancel_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('management_review_schedules', function (Blueprint $table) {
            $table->dropColumn('meeting_cancel_reason');
			$table->dropColumn('status');
        });
    }
}
