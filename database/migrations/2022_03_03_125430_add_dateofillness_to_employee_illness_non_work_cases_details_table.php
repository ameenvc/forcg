<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDateofillnessToEmployeeIllnessNonWorkCasesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_illness_non_work_cases_details', function (Blueprint $table) {
            $table->date('dateofillness')->after('employee_num')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_illness_non_work_cases_details', function (Blueprint $table) {
            $table->dropColumn('dateofillness');
        });
    }
}
