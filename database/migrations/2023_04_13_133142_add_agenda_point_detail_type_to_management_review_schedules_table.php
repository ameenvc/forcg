<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAgendaPointDetailTypeToManagementReviewSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('management_review_schedules', function (Blueprint $table) {
            $table->string('agenda_point_detail_type',200)->after('agenda_points');
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
            $table->dropColumn('agenda_point_detail_type');
        });
    }
}
