<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManagementReviewScheduleNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('management_review_schedule_notes', function (Blueprint $table) {
            $table->id();
			$table->unsignedbigInteger('schedule_id');
			$table->unsignedbigInteger('agenda_list_id');
			$table->text('minute_notes');
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
        Schema::dropIfExists('management_review_schedule_notes');
    }
}
