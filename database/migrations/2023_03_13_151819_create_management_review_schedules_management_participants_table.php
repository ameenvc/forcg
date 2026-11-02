<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManagementReviewSchedulesManagementParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('management_review_schedules_management_participants', function (Blueprint $table) {
            $table->id();
			$table->unsignedbigInteger('schedule_id');
			$table->unsignedbigInteger('manage_participant_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('management_review_schedules_management_participants');
    }
}
