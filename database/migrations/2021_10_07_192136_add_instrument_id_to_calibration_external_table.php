<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInstrumentIdToCalibrationExternalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calibration_external', function (Blueprint $table) {
             $table->bigInteger('instrument_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('calibration_external', function (Blueprint $table) {
            $table->dropColumn('instrument_id');
        });
    }
}
