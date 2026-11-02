<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDepartmentReportedByAndPositionFiledInNearmissTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('nearmiss', 'position') && !Schema::hasColumn('nearmiss', 'department_reported_by')) {
            Schema::table('nearmiss', function (Blueprint $table) {
                $table->string('position', 255)->after('area');
                $table->string('department_reported_by', 255)->after('area');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('nearmiss', 'position') && Schema::hasColumn('nearmiss', 'department_reported_by')) {
            Schema::table('nearmiss', function (Blueprint $table) {
                $table->dropColumn('position');
                $table->dropColumn('department_reported_by');
            });
        }
    }
}
