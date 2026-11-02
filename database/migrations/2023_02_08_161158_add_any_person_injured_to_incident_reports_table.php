<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAnyPersonInjuredToIncidentReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('incident_reports', function (Blueprint $table) {
             $table->string('any_person_injured',20)->after('incident_type');
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
            $table->dropColumn('any_person_injured');
        });
    }
}
