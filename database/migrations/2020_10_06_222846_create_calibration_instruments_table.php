<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalibrationInstrumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('calibration_instruments')) {
            Schema::create('calibration_instruments', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('Instrument_no')->nullable('true');
                $table->string('Instrument_desc', 5000);
                $table->string('Instru_Manufacturer', 500);
                $table->string('Department')->nullable('true');
                $table->string('Holder')->nullable('true');
                $table->string('Capacity')->nullable('true');
                $table->string('Type')->nullable('true');
                $table->string('Calib_procedure', 5000)->nullable('true');
                $table->string('Agency', 500)->nullable('true');
                $table->string('Frequency')->nullable('true');
                $table->string('Status', 500)->nullable('true');
                $table->date('duedate1')->nullable('true');
                $table->date('duedate2')->nullable('true');
                $table->date('duedate3')->nullable('true');
                $table->date('duedate4')->nullable('true');
                $table->string('Duedate')->nullable('true');
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
        Schema::dropIfExists('calibration_instruments');
    }
}
