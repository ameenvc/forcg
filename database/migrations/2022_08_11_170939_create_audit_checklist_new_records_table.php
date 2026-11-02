<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditChecklistNewRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_checklist_new_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedbigInteger('audit_schedule_id')->nullable();
            $table->unsignedbigInteger('checklist_id')->nullable();
            $table->unsignedbigInteger('title_id')->nullable();
            $table->unsignedbigInteger('points_id')->nullable();
            $table->unsignedbigInteger('department_id')->nullable();
            $table->string('audit_checklist_id',255)->nullable();
            $table->integer('total_checklist_qns')->nullable();
            $table->integer('completed_qns')->nullable();
            $table->integer('remaining_qns')->nullable();
            $table->date('audit_date')->nullable();
			$table->float('completed_percentage')->nullable();
			$table->string('completed_status',255)->nullable();
            $table->string('record_status',220)->nullable();
            $table->integer('count')->nullable();		
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
        Schema::dropIfExists('audit_checklist_new_records');
    }
}
