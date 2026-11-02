<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeInjuryRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('employee_injury_record')) {
            Schema::create('employee_injury_record', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('e_number');
                $table->string('e_name');
                $table->string('dob');
                $table->string('department');
                $table->string('position');
                $table->string('dateofinjury');
                $table->string('timeofinjury');
                $table->text('natureofinjury');
                $table->string('bodily_location');
                $table->string('location');
                $table->string('causeofinjury');
                $table->string('things_involved');
                $table->text('things_involved_details')->nullable();
                $table->string('witness');
                $table->string('reported_to_supervisor');
                $table->string('treatment_provided');
                $table->text('treatment_details')->nullable();
                $table->string('injured_returnto_work');
                $table->text('injured_returnto_work_details')->nullable();
                $table->string('creator_name');
                $table->string('creator_email');
                $table->string('creator_position');
                $table->string('creator_department');
                $table->string('created_date');
                $table->string('medical_leaves')->nullable();
                $table->string('status')->nullable();
                $table->string('last_update')->nullable();
                $table->timestamps();
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
        Schema::dropIfExists('employee_injury_record');
    }
}
