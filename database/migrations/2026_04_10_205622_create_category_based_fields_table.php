<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryBasedFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_based_fields', function (Blueprint $table) {
            $table->id();
            $table->string('module_name')->nullable();
			$table->string('column_name')->nullable();
			$table->string('field_name')->nullable();
			$table->integer('column_position')->nullable();
			$table->integer('column_filter')->nullable();
            $table->string('category_name')->nullable();
			$table->boolean('is_active')->default(false);
            $table->boolean('is_dragdropable')->default(false);
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
        Schema::dropIfExists('category_based_fields');
    }
}
