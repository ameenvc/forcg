<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObjectiveCategoryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('objective_category_details', function (Blueprint $table) {
            $table->id();
            $table->string('objective_category');
            $table->string('objective_name');
            $table->text('objective_description');
            $table->string('objective_target');
            $table->string('objective_unit');
            $table->text('objective_methodology');
            $table->text('objective_resources');
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
        Schema::dropIfExists('objective_category_details');
    }
}
