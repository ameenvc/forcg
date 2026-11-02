<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDepartmentIdNullableInAuditChecklistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audit_checklist', function (Blueprint $table) {
            $table->unsignedbigInteger('department_id')->nullable()->change();
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
            $table->unsignedbigInteger('department_id')->nullable(false)->change();
        });
    }
}
