<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmergencyDrillReportDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emergency_drill_report_details', function (Blueprint $table) {
            $table->id();
			$table->string('report_number');
			$table->unsignedbigInteger('emergency_details_id');
			$table->string('drill_type',220);
			$table->unsignedbigInteger('location_id')->nullable();
			$table->date('drill_date');
			$table->string('time_alarm_sounded',20);
			$table->string('time_drill_conducted',20);
			$table->string('time_evacuated',20);
			$table->string('drill_team');
			$table->string('drill_team_other')->nullable();
			$table->string('drill_start_situation',220);
			$table->string('drill_start_situation_other',220)->nullable();
			$table->string('weather',50);
			$table->string('weather_other',100)->nullable();
			$table->Integer('participants_number');
			$table->Integer('employees_working_area_num');
			$table->Integer('evacuation_count');
			$table->string('problem_encountered');
			$table->string('problem_encountered_other',220)->nullable();
			$table->string('evacuate_immediately',10);
			$table->string('checked_confined_areas',10);
			$table->string('emergency_informed',10);
			$table->string('equipment_function',10);
			$table->string('evacuated_all',10);
			$table->string('remain_outside',10);
			$table->string('emergency_team_knowledgeable',10);
			$table->string('drill_conducted_orderly',10);
			$table->string('evacuation_speed',15);
			$table->string('procedure_effectiveness',15);
			$table->string('drill_communication',15);
			$table->string('drill_rating',15);
			$table->string('mitigation');
			$table->string('mitigation_other',220)->nullable();
			$table->text('comments')->nullable();
			$table->Integer('count');
			$table->unsignedbigInteger('created_by');
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
        Schema::dropIfExists('emergency_drill_report_details');
    }
}
