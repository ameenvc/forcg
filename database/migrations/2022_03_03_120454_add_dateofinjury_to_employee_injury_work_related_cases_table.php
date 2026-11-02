<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDateofinjuryToEmployeeInjuryWorkRelatedCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_injury_work_related_cases', function (Blueprint $table) {
            $table->date('dateofinjury')->after('employee_num')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_injury_work_related_cases', function (Blueprint $table) {
            $table->dropColumn('dateofinjury');
        });
    }
}
