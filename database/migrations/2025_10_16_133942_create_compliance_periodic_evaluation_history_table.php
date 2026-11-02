<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompliancePeriodicEvaluationHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compliance_periodic_evaluation_history', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('compliance_id')->nullable();
            $table->string('old_status')->nullable();
            $table->string('new_status')->nullable();
            $table->string('periodic_evaluation_by')->nullable();
            $table->dateTime('periodic_evaluation_at')->nullable();
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
        Schema::dropIfExists('compliance_periodic_evaluation_history');
    }
}
