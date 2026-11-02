<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmergencyResponseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emergency_response_details', function (Blueprint $table) {
            $table->id();
			$table->unsignedbigInteger('department_id');
			$table->unsignedbigInteger('location_id')->nullable();
			$table->string('emergency_situation_name',220);
			$table->text('emergency_situation_desp');
			$table->string('frequency',220);
			$table->string('drill_team_id');
			$table->date('drill_date_last')->nullable();
			$table->date('drill_date_next');
			$table->unsignedbigInteger('added_by');
			$table->string('status',50)->nullable();
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
        Schema::dropIfExists('emergency_response_details');
    }
}
