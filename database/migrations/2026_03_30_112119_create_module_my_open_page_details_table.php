<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModuleMyOpenPageDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_my_open_page_details', function (Blueprint $table) {
            $table->id();
            $table->string('module_name');
			$table->string('column_name');
			$table->integer('column_position');
			$table->integer('column_filter');
			$table->boolean('is_active')->default(false);
            $table->boolean('is_dragdropable')->default(true);
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
        Schema::dropIfExists('module_my_open_page_details');
    }
}
