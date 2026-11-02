<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnviaspectsName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('enviaspects')) {
            Schema::create('enviaspects', function (Blueprint $table) {
                $table->bigIncrements('slno');
                $table->integer('Eid')->nullable('true');
                $table->string('enviaspects')->nullable('true');
                $table->string('ER')->nullable('true');
                $table->string('potential')->nullable('true');
                $table->decimal('A')->nullable('true');
                $table->decimal('B')->nullable('true');
                $table->decimal('C')->nullable('true');
                $table->decimal('D')->nullable('true');
                $table->decimal('E')->nullable('true');
                $table->integer('total')->nullable('true');
                $table->string('HS')->nullable('true');
                $table->string('status')->default('Review Pending');
                $table->string('control')->nullable('true');
                $table->string('created_at')->nullable('true');
                $table->string('reviewed_at')->nullable('true');
                $table->string('department')->nullable('true');
                $table->string('posted_by')->nullable('true');
                $table->string('approved_by')->nullable('true');
                $table->string('reviewed_by')->nullable('true');
                $table->integer('aspect_id')->default('0');
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
        Schema::dropIfExists('enviaspects');
    }
}
