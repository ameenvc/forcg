<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInjuredPersonPositionToIncidentReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('incident_reports', function (Blueprint $table) {
            $table->string('injured_person_position',50)->after('empid')->nullable();
			$table->string('injured_person_dept',50)->after('injured_person_position')->nullable();
			$table->string('injured_person_oth_name',50)->after('injured_person_dept')->nullable();
			$table->string('injured_person_oth_empid',50)->after('injured_person_oth_name')->nullable();
			$table->string('injured_person_oth_position',50)->after('injured_person_oth_empid')->nullable();
			$table->string('injured_person_oth_dept',50)->after('injured_person_oth_position')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('incident_reports', function (Blueprint $table) {
            $table->dropColumn('injured_person_position');
			$table->dropColumn('injured_person_dept');
			$table->dropColumn('injured_person_oth_name');
			$table->dropColumn('injured_person_oth_empid');
			$table->dropColumn('injured_person_oth_position');
			$table->dropColumn('injured_person_oth_dept');
        });
    }
}
