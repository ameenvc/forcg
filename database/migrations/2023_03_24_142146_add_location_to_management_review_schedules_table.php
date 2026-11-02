<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocationToManagementReviewSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('management_review_schedules', function (Blueprint $table) {
            $table->string('location',220)->after('time');
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
            $table->dropColumn('location');
        });
    }
}
