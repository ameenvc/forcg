<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModuleRegisterPageDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_register_page_details', function (Blueprint $table) {
            $table->id();
			$table->string('module_name');
			$table->string('column_name');
			$table->integer('column_position');
			$table->integer('column_filter');
			$table->boolean('is_active')->default(false);
			$table->unsignedBigInteger('enabled_by_user');
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
        Schema::dropIfExists('module_register_page_details');
    }
}
