<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmployeeIdToEmployeeHealthRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_health_record', function (Blueprint $table) {
            $table->unsignedbigInteger('employee_id')->after('remarks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_health_record', function (Blueprint $table) {
            $table->dropColumn('employee_id');
        });
    }
}
