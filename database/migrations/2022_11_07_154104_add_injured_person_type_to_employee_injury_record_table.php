<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInjuredPersonTypeToEmployeeInjuryRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_injury_record', function (Blueprint $table) {
            $table->string('injured_person_type',50)->after('year')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_injury_record', function (Blueprint $table) {
            $table->dropColumn('injured_person_type');
        });
    }
}
