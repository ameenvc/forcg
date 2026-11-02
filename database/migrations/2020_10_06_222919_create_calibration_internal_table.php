<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalibrationInternalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('calibration_internal')) {
            Schema::create('calibration_internal', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('Instrument_no');
                $table->integer('Acc_error');
                $table->string('M_instru', 500);
                $table->string('Certificate_no', 500);
                $table->string('M_reading', 500);
                $table->string('Instrument_reading', 500);
                $table->string('Error', 500);
                $table->string('Remarks', 500);
                $table->string('Report_no', 500);
                $table->string('Adj_made', 500);
                $table->string('Calibrated_by', 500);
                $table->string('Verified_by', 500);
                $table->string('Dateofcal', 200);
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
        Schema::dropIfExists('calibration_internal');
    }
}
