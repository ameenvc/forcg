<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalibrationExternalfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('calibration_externalfiles')) {
            Schema::create('calibration_externalfiles', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('Instrument_no', 500)->nullable('true');
                $table->string('Type', 500)->nullable('true');
                $table->string('Filename', 500)->nullable('true');
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
        Schema::dropIfExists('calibration_externalfiles');
    }
}
