<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeIllnessRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('employee_illness_record')) {
            Schema::create('employee_illness_record', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('e_number');
                $table->string('e_name');
                $table->string('dob');
                $table->string('department');
                $table->string('position');
                $table->string('dateofillness');
                $table->string('timeofillness');
                $table->text('natureofillness');
                $table->string('location');
                $table->string('causeofillness');
                $table->string('symptoms')->nullable();
                $table->string('things_involved');
                $table->text('things_involved_details')->nullable();
                $table->string('pandamic');
                $table->text('pandamic_details')->nullable();
                $table->text('pandemic_contacts')->nullable();
                $table->string('witness');
                $table->string('reported_to_supervisor');
                $table->string('treatment_provided');
                $table->text('treatment_details')->nullable();
                $table->string('illed_returnto_work');
                $table->text('illed_returnto_work_details')->nullable();
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
        Schema::dropIfExists('employee_illness_record');
    }
}
