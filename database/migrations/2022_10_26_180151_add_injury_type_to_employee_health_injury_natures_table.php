<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInjuryTypeToEmployeeHealthInjuryNaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_health_injury_natures', function (Blueprint $table) {
            $table->string('injury_type',50)->after('name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_health_injury_natures', function (Blueprint $table) {
            $table->dropColumn('injury_type');
        });
    }
}
