<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIllnessIdToEmployeeIllnessWitnessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_illness_witness', function (Blueprint $table) {
            $table->unsignedBigInteger('illness_id')->after('number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_illness_witness', function (Blueprint $table) {
            $table->dropColumn('illness_id');
        });
    }
}
