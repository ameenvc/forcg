<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRevertName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('revert')) {
            Schema::create('revert', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('Rid')->nullable();
                $table->string('pro_status')->default('Waiting for HOD Review');
                $table->string('owner')->nullable();
                $table->text('reason')->nullable();
                $table->string('remarks', 1000)->nullable();
                $table->integer('postedby')->nullable();
                $table->string('person_control')->nullable();
                $table->string('person_monitor')->nullable();
                $table->timestamp('createdat');
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
        Schema::dropIfExists('revert');
    }
}
