<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModuleBladePageDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_blade_page_details', function (Blueprint $table) {
            $table->id();
            $table->string('module_name');
			$table->string('page_name');
			$table->string('group_name')->nullable();
			$table->string('sub_module')->nullable();
            $table->string('blade_path')->nullable();
            $table->string('route_path')->nullable();
			$table->boolean('is_display_page')->default(false);
			$table->boolean('is_target_page')->default(false);
            $table->longText('field_index')->nullable();
            $table->string('structure')->nullable();
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
        Schema::dropIfExists('module_blade_page_details');
    }
}
