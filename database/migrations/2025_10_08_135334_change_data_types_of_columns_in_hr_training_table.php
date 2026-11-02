<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDataTypesOfColumnsInHrTrainingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('hr_training', function (Blueprint $table) {
            $table->longText('emp_name')->change();
            $table->longText('emp_number')->change();
            $table->longText('emp_department')->change();
            $table->longText('position')->change();
            $table->longText('benefits')->change();
            $table->longText('employee_id')->change();
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hr_training', function (Blueprint $table) {
            $table->string('emp_name',1000)->change();
            $table->string('emp_number',2000)->change();
            $table->string('emp_department',20)->change();
            $table->string('position',1000)->change();
            $table->string('benefits',1500)->change();
            $table->string('employee_id',100)->change();
        });
    }
}
