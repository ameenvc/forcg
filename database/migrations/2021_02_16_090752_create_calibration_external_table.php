<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalibrationExternalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('calibration_external')) {
            Schema::create('calibration_external', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('Instrument_no')->nullable();
                $table->string('Duedate')->nullable();
                $table->string('Dateofcal')->nullable();
                $table->string('Status')->nullable();
                $table->string('Report_no')->nullable();
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
        Schema::dropIfExists('calibration_external');
    }
}
