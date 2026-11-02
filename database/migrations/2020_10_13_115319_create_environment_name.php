<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnvironmentName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('environment')) {
            Schema::create('environment', function (Blueprint $table) {
                $table->bigIncrements('Eid');
                $table->dateTime('date')->nullable('true');
                $table->string('team')->nullable('true');
                $table->string('department')->nullable('true');
                $table->string('activity')->nullable('true');
                $table->string('created_at')->nullable('true');
                $table->string('reviewed_at')->nullable('true');
                $table->string('status')->default('Review Pending');
                $table->string('proposechanges', 2000)->nullable('true');
                $table->string('useremail')->default('a@gmail.com');
                $table->integer('count')->default('0');
                $table->string('location')->nullable('true');
                $table->string('created_by')->nullable('true');
                $table->string('reviewed_by')->nullable('true');
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
        Schema::dropIfExists('environment');
    }
}
