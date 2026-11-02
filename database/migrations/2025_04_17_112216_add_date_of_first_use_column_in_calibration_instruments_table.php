<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDateOfFirstUseColumnInCalibrationInstrumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calibration_instruments', function (Blueprint $table) {
            $table->date('date_of_first_use')->nullable()->after('least_count');
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
            $table->dropColumn('date_of_first_use');
        });
    }
}
