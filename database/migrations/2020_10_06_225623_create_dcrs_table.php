<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDcrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('dcrs')) {
            Schema::create('dcrs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('count')->default('0');
                $table->string('doc_id')->nullable('true');
                $table->string('department')->nullable('true');
                $table->string('name')->nullable('true');
                $table->string('rid')->nullable('true');
                $table->string('doctype')->nullable('true');
                $table->string('type')->nullable('true');
                $table->longText('documents')->nullable('true');
                $table->longText('affected')->nullable('true');
                $table->longText('reason')->nullable('true');
                $table->timestamp('date')->nullable('true');
                $table->string('hod')->nullable('true');
                $table->string('postedby')->nullable('true');
                $table->string('status')->nullable('true');
                $table->timestamp('reviewed_at')->nullable('true');
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
        Schema::dropIfExists('dcrs');
    }
}
