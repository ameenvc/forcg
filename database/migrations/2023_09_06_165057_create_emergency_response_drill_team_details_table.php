<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmergencyResponseDrillTeamDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emergency_response_drill_team_details', function (Blueprint $table) {
            $table->id();
			$table->unsignedbigInteger('emergency_response_details_id');
			$table->unsignedbigInteger('drill_team_id');
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
        Schema::dropIfExists('emergency_response_drill_team_details');
    }
}
