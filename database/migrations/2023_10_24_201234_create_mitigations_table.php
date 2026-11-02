<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMitigationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('mitigations')) {
            Schema::create('mitigations', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('risk_id');
                $table->unsignedInteger('owner');
                $table->unsignedInteger('department_id');
                $table->string('action_name')->nullable();
                $table->text('action_description')->nullable();
                $table->string('priority_level')->nullable();
                $table->string('link_other_risk')->nullable();
                $table->dateTime('due_date')->nullable();
                $table->string('status')->default(1);
                $table->string('resource_required')->nullable();
                $table->string('cost_estimate')->nullable();
                $table->timestamps();
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
        Schema::dropIfExists('mitigations');
    }
}
