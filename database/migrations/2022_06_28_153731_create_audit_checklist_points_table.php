<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditChecklistPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_checklist_points', function (Blueprint $table) {
            $table->id();
			$table->string('points',225);
			$table->string('purpose',225)->nullable();
			$table->string('items_verified',225)->nullable();
			$table->unsignedbigInteger('checklist_title_id');
			$table->unsignedbigInteger('checklist_id');
			$table->unsignedbigInteger('department_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('audit_checklist_points');
    }
}
