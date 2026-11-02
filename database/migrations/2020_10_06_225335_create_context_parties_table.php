<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContextPartiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('context_parties')) {
            Schema::create('context_parties', function (Blueprint $table) {
                $table->bigIncrements('Cid');
                $table->integer('count')->default('0');
                $table->string('partyid')->nullable('true');
                $table->string('status')->nullable('true');
                $table->string('intr_party', 500)->nullable('true');
                $table->string('type')->nullable('true');
                $table->string('purpose', 1000)->nullable('true');
                $table->string('posted_by')->nullable('true');
                $table->string('created_at')->nullable('true');
                $table->string('updated_at')->nullable('true');
                $table->string('approved_by')->nullable('true');
                $table->string('closed_by')->nullable('true');
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
        Schema::dropIfExists('context_parties');
    }
}
