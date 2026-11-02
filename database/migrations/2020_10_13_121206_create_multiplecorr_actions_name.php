<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMultiplecorrActionsName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('multiplecorr_actions')) {
            Schema::create('multiplecorr_actions', function (Blueprint $table) {
                $table->bigIncrements('Sno');
                $table->integer('id')->nullable();
                $table->string('action_file', 2000)->nullable();
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
        Schema::dropIfExists('multiplecorr_actions');
    }
}
