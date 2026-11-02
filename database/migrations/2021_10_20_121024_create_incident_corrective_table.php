<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncidentCorrectiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incident_corrective', function (Blueprint $table) {
            $table->id();
			$table->Integer('count')->default(0);
			$table->string('issueid');
			$table->string('incident_reportno');
			$table->string('corrective_id');
			$table->string('category');
			$table->year('year');
			$table->string('file_name')->nullable();
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
        Schema::dropIfExists('incident_corrective');
    }
}
