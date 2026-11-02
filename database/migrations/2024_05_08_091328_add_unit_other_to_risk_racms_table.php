<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUnitOtherToRiskRacmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('risk_racms', function (Blueprint $table) {
            $table->string('unit_other')->nullable()->after('unit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('risk_racms', function (Blueprint $table) {
            $table->dropColumn('unit_other');
        });
    }
}
