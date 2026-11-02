<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddManagementParticipantsOtherEmailToManagementReviewSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('management_review_schedules', function (Blueprint $table) {
            $table->string('management_participants_other_email',255)->after('management_participants_other')->nullable();
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
            $table->dropColumn('management_participants_other_email');
        });
    }
}
