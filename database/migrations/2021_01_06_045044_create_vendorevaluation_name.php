<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorevaluationName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendorevaluation_name', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->Integer('vid')->nullable();
            $table->string('type')->nullable();
            $table->string('proc_name',500)->nullable();
            $table->Integer('proc_score')->nullable();
            $table->Integer('proc_total')->nullable();
            $table->string('proc_evaluated_by')->nullable();
            $table->string('proc_evaluated_at')->nullable();
            $table->string('hse_name')->nullable();
            $table->Integer('hse_score')->nullable();
            $table->Integer('hse_total')->nullable();
            $table->string('hse_evaluated_by')->nullable();
            $table->string('hse_evaluated_at')->nullable();
            $table->string('enduser_name')->nullable();
            $table->Integer('end_score')->nullable();
            $table->Integer('end_total')->nullable();
            $table->string('recommend')->nullable();
            $table->string('end_evaluated_by')->nullable();
            $table->string('end_evaluated_at')->nullable();
            $table->Integer('total_score')->nullable();
            $table->Integer('final_score')->nullable();
            $table->decimal('percentage')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendorevaluation_name');
    }
}
