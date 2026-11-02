<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOppremarksName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('oppremarks')) {
            Schema::create('oppremarks', function (Blueprint $table) {
                $table->bigIncrements('slno');
                $table->integer('Oid')->nullable();
                $table->text('oppremarks')->nullable();
                $table->string('postedby')->nullable();
                $table->string('ostatus')->nullable();
                $table->timestamp('created_at');
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
        Schema::dropIfExists('oppremarks');
    }
}
