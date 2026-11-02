<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIllnessTypeToEmployeeHealthIllnessNaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_health_illness_natures', function (Blueprint $table) {
            $table->string('illness_type',50)->after('name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_health_illness_natures', function (Blueprint $table) {
            $table->dropColumn('illness_type');
        });
    }
}
