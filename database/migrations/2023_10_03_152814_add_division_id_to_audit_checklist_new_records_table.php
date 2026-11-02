<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDivisionIdToAuditChecklistNewRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audit_checklist_new_records', function (Blueprint $table) {
            $table->unsignedbigInteger('division_id')->nullable()->after('department_id');
			$table->unsignedbigInteger('area_id')->nullable()->after('division_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audit_checklist_new_records', function (Blueprint $table) {
             $table->dropColumn('division_id');
			 $table->dropColumn('area_id');
        });
    }
}
