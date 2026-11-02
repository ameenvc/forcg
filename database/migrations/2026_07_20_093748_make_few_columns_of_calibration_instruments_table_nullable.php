<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeFewColumnsOfCalibrationInstrumentsTableNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calibration_instruments', function (Blueprint $table) {
            $table->string('Instrument_desc')->nullable()->change();
            $table->string('Instru_Manufacturer')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('calibration_instruments', function (Blueprint $table) {
            $table->string('Instrument_desc')->nullable(false)->change();
            $table->string('Instru_Manufacturer')->nullable(false)->change();
        });
    }
}
