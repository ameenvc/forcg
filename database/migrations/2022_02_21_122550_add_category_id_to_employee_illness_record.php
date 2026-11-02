<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryIdToEmployeeIllnessRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_illness_record', function (Blueprint $table) {
            $table->unsignedbigInteger('category_id')->after('id');
			$table->string('ill_type',50)->after('timeofillness');
			$table->text('workrelated_illness')->after('ill_type')->nullable();
			$table->string('nonworkrelated_illness',150)->after('workrelated_illness')->nullable();
			$table->text('nonworkrelated_illness_other')->after('nonworkrelated_illness')->nullable();
			$table->text('treatmentdetails_other')->after('treatment_details')->nullable();
			$table->string('pulse',100)->after('illed_bs_level')->nullable();
			$table->string('temperature',100)->after('pulse')->nullable();
			$table->string('respiratory_rate',100)->after('temperature')->nullable();
			$table->string('weight',100)->after('respiratory_rate')->nullable();
			$table->string('height',100)->after('weight')->nullable();
			$table->year('year')->after('report_num')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_illness_record', function (Blueprint $table) {
            $table->dropColumn('category_id');
			$table->dropColumn('ill_type');
			$table->dropColumn('workrelated_illness');
			$table->dropColumn('nonworkrelated_illness');
			$table->dropColumn('nonworkrelated_illness_other');
			$table->dropColumn('treatmentdetails_other');
			$table->dropColumn('pulse');
			$table->dropColumn('temperature');
			$table->dropColumn('respiratory_rate');
			$table->dropColumn('weight');
			$table->dropColumn('height');
			$table->dropColumn('year');			
        });
    }
}
