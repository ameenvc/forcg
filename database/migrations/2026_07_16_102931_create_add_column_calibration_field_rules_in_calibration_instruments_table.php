<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddColumnCalibrationFieldRulesInCalibrationInstrumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calibration_instruments', function (Blueprint $table) {
            $table->longText('calibration_field_rules')->nullable()->after('dateofcal');
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
            $table->dropColumn('calibration_field_rules');
        });
    }
}
