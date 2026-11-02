<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditChecklistNewRecordDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_checklist_new_record_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedbigInteger('audit_id')->nullable();
            $table->unsignedbigInteger('checklist_id')->nullable();
            $table->unsignedbigInteger('title_id')->nullable();
            $table->unsignedbigInteger('points_id')->nullable();
            $table->text('items_verified_records')->nullable();
            $table->unsignedbigInteger('conformance_id')->nullable();
            $table->text('remarks')->nullable();
            $table->date('record_created_date')->nullable();
            $table->string('audit_checklist_id',255)->nullable();
            $table->text('pointsname')->nullable();
            $table->text('items_verified')->nullable();
            $table->text('purpose')->nullable();
            $table->string('title_name',255)->nullable();
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
        Schema::dropIfExists('audit_checklist_new_record_details');
    }
}
