<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\AuditChecklistNewRecord;

class AddAuditChecklistIdYearToAuditChecklistNewRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audit_checklist_new_records', function (Blueprint $table) {
             $table->string('audit_checklist_id_year',255)->after('audit_checklist_id')->nullable();
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
            $table->dropColumn('audit_checklist_id_year');
        });
    }
}
