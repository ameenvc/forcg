<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOhsriskName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('ohsrisk')) {
            Schema::create('ohsrisk', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('Rid')->nullable();
                $table->string('riskid')->nullable();
                $table->string('department')->nullable();
                $table->string('risk_status')->default('Review Pending')->nullable();
                $table->string('active')->default('Yes');
                $table->string('category')->nullable();
                $table->string('R_NR_EM')->nullable();
                $table->string('hazards', 2000)->nullable();
                $table->string('affect', 2000)->nullable();
                $table->string('control', 2000)->nullable();
                $table->integer('OrS')->nullable();
                $table->integer('OrL')->nullable();
                $table->integer('OrR')->nullable();
                $table->string('additional', 2000)->nullable();
                $table->integer('RS')->nullable();
                $table->integer('RL')->nullable();
                $table->integer('RR')->nullable();
                $table->string('monitor', 500)->nullable();
                $table->string('proc_no')->nullable();
                $table->string('procedur', 1000)->nullable();
                $table->string('created_at')->nullable();
                $table->string('reviewed_at')->nullable();
                $table->string('approvedby')->nullable();
                $table->dateTime('time')->nullable();
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
        Schema::dropIfExists('ohsrisk');
    }
}
