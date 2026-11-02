<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDuedateToEmergencyResponseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('emergency_response_details', function (Blueprint $table) {
			$table->date('duedate')->after('drill_date_next')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('emergency_response_details', function (Blueprint $table) {
            $table->dropColumn('duedate');
        });
    }
}
