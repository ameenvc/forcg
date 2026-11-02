<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWastedescTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('wastedesc')) {
            Schema::create('wastedesc', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('waste_type');
                $table->string('wastedesc');
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
        Schema::dropIfExists('wastedesc');
    }
}
