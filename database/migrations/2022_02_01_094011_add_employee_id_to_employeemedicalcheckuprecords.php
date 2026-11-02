<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmployeeIdToEmployeemedicalcheckuprecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employeemedicalcheckuprecords', function (Blueprint $table) {
            $table->unsignedbigInteger('employee_id')->after('health_record_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employeemedicalcheckuprecords', function (Blueprint $table) {
            $table->dropColumn('employee_id');
        });
    }
}
