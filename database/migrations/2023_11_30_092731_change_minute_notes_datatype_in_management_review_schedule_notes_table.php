<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeMinuteNotesDatatypeInManagementReviewScheduleNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('management_review_schedule_notes', function (Blueprint $table) {
            $table->longText('minute_notes')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('management_review_schedule_notes', function (Blueprint $table) {
            $table->text('minute_notes')->change();
        });
    }
}
