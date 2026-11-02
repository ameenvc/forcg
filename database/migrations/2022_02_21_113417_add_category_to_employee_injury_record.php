<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryToEmployeeInjuryRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_injury_record', function (Blueprint $table) {
            $table->unsignedbigInteger('category_id')->after('id');
			$table->string('injury_type',50)->after('timeofinjury');
			$table->string('workrelated_injury',150)->after('injury_type')->nullable();
			$table->text('workrelated_injury_other')->after('workrelated_injury')->nullable();
			$table->text('nonwork_related_injury')->after('workrelated_injury_other')->nullable();
			$table->string('body_injured_other')->after('bodily_location')->nullable();
			$table->text('treatmentdetails_other')->after('treatment_details')->nullable();
			$table->string('pulse',100)->after('injured_bs_level')->nullable();
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
        Schema::table('employee_injury_record', function (Blueprint $table) {
            $table->dropColumn('category_id');
			$table->dropColumn('injury_type');
			$table->dropColumn('workrelated_injury');
			$table->dropColumn('workrelated_injury_other');
			$table->dropColumn('nonwork_related_injury');
			$table->dropColumn('body_injured_other');
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
