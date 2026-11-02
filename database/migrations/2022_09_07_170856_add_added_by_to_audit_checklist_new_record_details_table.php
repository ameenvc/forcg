<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddedByToAuditChecklistNewRecordDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audit_checklist_new_record_details', function (Blueprint $table) {
            $table->unsignedbigInteger('added_by')->after('title_name')->nullable();
			$table->unsignedbigInteger('updated_by')->after('added_by')->nullable();
			$table->date('record_updated_date')->after('updated_by')->nullable();
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
            $table->dropColumn('added_by');
			$table->dropColumn('updated_by');
			$table->dropColumn('record_updated_date');
        });
    }
}
