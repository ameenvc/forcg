<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeleteReasonToAuditChecklistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audit_checklist', function (Blueprint $table) {
			$table->unsignedbigInteger('added_by_user_id')->after('count')->nullable();
			$table->unsignedbigInteger('updated_by_user_id')->after('added_by_user_id')->nullable();
			$table->text('delete_reason')->after('updated_by_user_id')->nullable();
            $table->boolean('is_deleted')->after('delete_reason')->default(false);
			$table->unsignedbigInteger('deleted_by_user_id')->after('delete_reason')->nullable();
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
            $table->dropColumn('added_by_user_id');
			$table->dropColumn('updated_by_user_id');
			$table->dropColumn('delete_reason');
			$table->dropColumn('is_deleted');
			$table->dropColumn('deleted_by_user_id');
        });
    }
}
