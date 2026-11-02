<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRecordUpdatedDateToAuditChecklistNewRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audit_checklist_new_records', function (Blueprint $table) {
            $table->date('record_updated_date')->after('record_status')->nullable();
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
            $table->dropColumn('record_updated_date');
        });
    }
}
