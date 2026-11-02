<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReportNumToEmployeeIllnessRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_illness_record', function (Blueprint $table) {
            $table->string('illed_bp_diastolic',100)->after('created_date')->nullable();
            $table->string('illed_bp_systolic',100)->after('illed_bp_diastolic')->nullable();
            $table->string('illed_bs_level',100)->after('illed_bp_systolic')->nullable();
            $table->string('remarks')->after('illed_bs_level')->nullable();
			$table->string('report_num')->after('last_update')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_illness_record', function (Blueprint $table) {
             $table->dropColumn('report_num');
			 $table->dropColumn('illed_bp_diastolic');
			 $table->dropColumn('illed_bp_systolic');
			 $table->dropColumn('illed_bs_level');
			 $table->dropColumn('remarks');
        });
    }
}
