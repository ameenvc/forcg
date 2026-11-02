<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsDeletedToAuditChecklistNewRecordDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audit_checklist_new_record_details', function (Blueprint $table) {
            $table->boolean('is_deleted')->after('record_updated_date')->default(false); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audit_checklist_new_record_details', function (Blueprint $table) {
            $table->dropColumn('is_deleted');
        });
    }
}
