<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManagementReviewScheduleCorrectivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('management_review_schedule_correctives', function (Blueprint $table) {
            $table->id();
			$table->unsignedbigInteger('corrective_id');
			$table->unsignedbigInteger('schedule_id');
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
        Schema::dropIfExists('management_review_schedule_correctives');
    }
}
