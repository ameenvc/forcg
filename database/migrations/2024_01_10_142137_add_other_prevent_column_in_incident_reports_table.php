<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOtherPreventColumnInIncidentReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('incident_reports', function (Blueprint $table) {
            $table->string('prevent_other')->nullable()->after('prevent');
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
            $table->dropColumn('prevent_other');
        });
    }
}
