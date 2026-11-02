<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EmployeeDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('employee_details')) {
            Schema::create('employee_details', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('emp_number');
                $table->string('emp_name');
                $table->string('emp_position');
                $table->string('emp_department');
                $table->string('joining_date');
                $table->string('emp_dob');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_details');
    }
}
