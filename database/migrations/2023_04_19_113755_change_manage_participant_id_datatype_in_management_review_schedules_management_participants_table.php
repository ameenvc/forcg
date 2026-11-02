<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeManageParticipantIdDatatypeInManagementReviewSchedulesManagementParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('management_review_schedules_management_participants', function (Blueprint $table) {
            $table->string('manage_participant_id',50)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('management_review_schedules_management_participants', function (Blueprint $table) {
            $table->unsignedbigInteger('manage_participant_id')->change();
        });
    }
}
