<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrJobName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('hr_job')) {
            Schema::create('hr_job', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('position_title', 600);
                $table->string('reports_to', 600);
                $table->string('dept');
                $table->string('job_code');
                $table->text('responsibilities');
                $table->text('job_authorities');
                $table->text('accountabilities');
                $table->text('hse');
                $table->string('internal');
                $table->string('external');
                $table->string('min_qual');
                $table->string('training_skills');
                $table->string('min_exp', 1000);
                $table->date('prepared_on');
                $table->string('created_at');
                $table->integer('added_by');
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
        Schema::dropIfExists('hr_job');
    }
}
