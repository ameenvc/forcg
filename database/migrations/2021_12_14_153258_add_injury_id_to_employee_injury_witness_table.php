<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInjuryIdToEmployeeInjuryWitnessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_injury_witness', function (Blueprint $table) {
            $table->unsignedBigInteger('injury_id')->after('number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_injury_witness', function (Blueprint $table) {
            $table->dropColumn('injury_id');
        });
    }
}
