<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRisksName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('risks')) {
            Schema::create('risks', function (Blueprint $table) {
                $table->bigIncrements('Rid');
                $table->integer('count')->default('0');
                $table->string('grp_id')->nullable();
                $table->string('riskgroup')->nullable();
                $table->string('riskarea')->nullable();
                $table->string('riskname')->nullable();
                $table->string('riskdec', 1000)->nullable();
                $table->string('riskdri', 1000)->nullable();
                $table->string('date')->nullable();
                $table->string('riskscr')->nullable();
                $table->string('impa')->nullable();
                $table->string('like')->nullable();
                $table->string('rank')->nullable();
                $table->string('status')->default('NO');
                $table->string('risk_response')->nullable();
                $table->string('mitigating_action', 2000)->nullable();
                $table->string('risk_owner')->nullable();
                $table->string('action_target')->nullable();
                $table->string('action_status')->default('Under review')->nullable();
                $table->string('actionst')->nullable();
                $table->string('review_date')->nullable();
                $table->string('remarks', 2000)->nullable();
                $table->string('reason', 2000)->nullable();
                $table->string('disapprovereason', 2000)->nullable();
                $table->string('postedby')->nullable();
                $table->string('reviewed_by')->nullable();
                $table->string('approved_by')->nullable();
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
        Schema::dropIfExists('risks');
    }
}
