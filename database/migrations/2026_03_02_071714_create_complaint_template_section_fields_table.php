<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintTemplateSectionFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaint_template_section_fields', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('complaint_template_section_id');
            $table->boolean('enable_field');
            $table->string('field_name');
            $table->string('field_type');
            $table->string('default_display_label');
            $table->string('field_display_label');
            $table->boolean('is_multiple');
            $table->boolean('field_is_mandatory');
            $table->bigInteger('updated_by');
            $table->integer('order');
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
        Schema::dropIfExists('complaint_template_section_fields');
    }
}
