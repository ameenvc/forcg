<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmergencyResponseDrillReportCorrectivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emergency_response_drill_report_correctives', function (Blueprint $table) {
            $table->id();
			$table->unsignedbigInteger('corrective_id');
			$table->unsignedbigInteger('drill_report_id');
			$table->string('drill_report_number');
			$table->Integer('count');
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
        Schema::dropIfExists('emergency_response_drill_report_correctives');
    }
}
