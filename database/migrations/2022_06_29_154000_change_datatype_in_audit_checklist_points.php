<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDatatypeInAuditChecklistPoints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audit_checklist_points', function (Blueprint $table) {
            $table->text('points')->change();
			$table->text('purpose')->change();
			$table->text('items_verified')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audit_checklist_points', function (Blueprint $table) {
            $table->string('points',225)->change();
			$table->string('purpose',225)->change();
			$table->string('items_verified',225)->change();
        });
    }
}
