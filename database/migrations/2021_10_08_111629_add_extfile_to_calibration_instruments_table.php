<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtfileToCalibrationInstrumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('calibration_instruments', 'extfile')) 
		{
			Schema::table('calibration_instruments', function (Blueprint $table) {
				$table->string('extfile',300)->after('Status')->nullable();
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
        Schema::table('calibration_instruments', function (Blueprint $table) {
            $table->dropColumn('extfile');
        });
    }
}
