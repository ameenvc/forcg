<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditChecklistTitlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_checklist_titles', function (Blueprint $table) {
            $table->id();
			$table->string('title',225);
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
        Schema::dropIfExists('audit_checklist_titles');
    }
}
