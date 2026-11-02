<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnviaspectRegulatoryRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enviaspect_regulatory_requirements', function (Blueprint $table) {
            $table->id();
            $table->text('description')->nullable();
            $table->double('weightage');
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
        Schema::dropIfExists('enviaspect_regulatory_requirements');
    }
}
