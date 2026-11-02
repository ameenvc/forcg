<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToAuditChecklistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audit_checklist', function (Blueprint $table) {
            $table->string('status',225)->after('deleted_by_user_id')->nullable();
			$table->unsignedbigInteger('approved_by_user_id')->after('status')->nullable();
			$table->string('approved_date')->after('approved_by_user_id')->nullable();
			$table->text('suggest_improvements')->after('approved_date')->nullable();
			$table->unsignedbigInteger('suggest_improvements_by_user_id')->after('suggest_improvements')->nullable();
			$table->string('delete_status',20)->after('suggest_improvements_by_user_id')->nullable();
			
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audit_checklist', function (Blueprint $table) {
            $table->dropColumn('status');
			$table->dropColumn('approved_by_user_id');
			$table->dropColumn('approved_date');
			$table->dropColumn('suggest_improvements');
			$table->dropColumn('suggest_improvements_by_user_id');
			$table->dropColumn('delete_status');
        });
    }
}
