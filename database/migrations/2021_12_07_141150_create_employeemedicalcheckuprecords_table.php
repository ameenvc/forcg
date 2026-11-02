<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeemedicalcheckuprecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employeemedicalcheckuprecords', function (Blueprint $table) {
            $table->id();
            $table->string('emp_number',50);
            $table->date('date_of_medical');
            $table->string('tobacco',10);
            $table->string('bp_diastolic',100);
            $table->string('bp_systolic',100);
            $table->string('bs_level',100);
            $table->string('remarks')->nullable();
            $table->unsignedBigInteger('health_record_id');
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
        Schema::dropIfExists('employeemedicalcheckuprecords');
    }
}
