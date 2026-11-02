<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrTrainingName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('hr_training')) {
            Schema::create('hr_training', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('emp_name', 1000);
                $table->string('position', 1000);
                $table->string('training_title', 1000);
                $table->string('training_type', 1000);
                $table->string('inhouse_outside', 2000);
                $table->string('training_provider', 2000);
                $table->string('training_agency', 2000)->nullable();
                $table->string('training_duration', 1000);
                $table->date('periods');
                $table->text('benefits');
                $table->float('status')->nullable();
                $table->integer('evaluatedby')->nullable();
                $table->string('evaluated_at', 1000)->nullable();
                $table->string('created_at');
                $table->integer('posted_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hr_training');
    }
}
