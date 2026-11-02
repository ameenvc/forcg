<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterHrTrainingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hr_training', function (Blueprint $table) {
            $table->string('emp_number',20)->after('emp_name')->nullable();
			$table->string('emp_department',20)->after('emp_number')->nullable();
			$table->timestamp('updated_at')->after('created_at')->nullable();
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
            $table->dropColumn('emp_number');
			$table->dropColumn('emp_department');
            $table->dropColumn('updated_at');
        });
    }
}
