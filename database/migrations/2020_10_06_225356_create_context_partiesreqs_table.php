<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContextPartiesreqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('context_partiesreqs')) {
            Schema::create('context_partiesreqs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('Cid')->nullable('true');
                $table->string('reqid')->nullable('true');
                $table->string('intr_party', 500)->nullable('true');
                $table->string('reqip', 2000)->nullable('true');
                $table->string('monitoring', 1000)->nullable('true');
                $table->string('accountability', 500)->nullable('true');
                $table->string('posted_by')->nullable('true');
                $table->string('created_at')->nullable('true');
                $table->string('reviewed_at')->nullable('true');
                $table->string('approvedby')->nullable('true');
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
        Schema::dropIfExists('context_partiesreqs');
    }
}
