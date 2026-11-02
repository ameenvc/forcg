<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManagementReviewSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('management_review_schedules', function (Blueprint $table) {
            $table->id();
			$table->date('date');
			$table->string('time',50);
			$table->string('agenda_type',150);
			$table->unsignedbigInteger('agenda_points');
			$table->string('participants',220);
			$table->string('management_participants',220);
			$table->string('management_participants_other',255)->nullable();
			$table->string('added_by',255);
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
        Schema::dropIfExists('management_review_schedules');
    }
}
