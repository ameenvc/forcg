<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToCalibrationInternalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		if(!Schema::hasColumn('calibration_internal', 'Status')) 
		{
			Schema::table('calibration_internal', function (Blueprint $table) {
				$table->string('Status',300)->nullable();
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
        Schema::table('calibration_internal', function (Blueprint $table) {
            $table->dropColumn('Status');
        });
    }
}
