<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManagementReviewSchedulesAgendaTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('management_review_schedules_agenda_types', function (Blueprint $table) {
            $table->id();
			$table->unsignedbigInteger('schedule_id');
			$table->unsignedbigInteger('agenda_type_id');
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
        Schema::dropIfExists('management_review_schedules_agenda_types');
    }
}
