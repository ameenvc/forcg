<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeIllnessNonWorkCasesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_illness_non_work_cases_details', function (Blueprint $table) {
            $table->id();
            $table->string('illness_id',20);
            $table->unsignedbigInteger('illness_record_id');
            $table->string('employee_num',150);
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
        Schema::dropIfExists('employee_illness_non_work_cases_details');
    }
}
