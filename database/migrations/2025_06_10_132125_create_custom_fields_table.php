<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_fields', function (Blueprint $table) {
            $table->id();
            $table->string('module_name');
            $table->string('sub_module');
            $table->string('field_name');
            $table->string('field_type');
            $table->string('field_placeholder')->nullable();
            $table->boolean('mandatory')->default(false);
            $table->json('target_pages');
            $table->json('display_pages');
            $table->boolean('deleted')->default(false);
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
        Schema::dropIfExists('custom_fields');
    }
}
