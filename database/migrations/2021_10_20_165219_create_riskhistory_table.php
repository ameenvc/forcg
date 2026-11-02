<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiskhistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('riskhistory', function (Blueprint $table) {
            $table->id();
            $table->integer('Rid')->nullable();
            $table->string('riskname')->nullable();
            $table->string('impa')->nullable();
            $table->string('like')->nullable();
            $table->string('rank')->nullable();
            $table->string('mitigating_action', 2000)->nullable();
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
        Schema::dropIfExists('riskhistory');
    }
}
