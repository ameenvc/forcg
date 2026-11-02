<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContextIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('context_issues')) {
            Schema::create('context_issues', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('count')->default('0')->nullable('true');
                $table->string('issueid')->nullable('true');
                $table->string('status')->nullable('true');
                $table->string('type')->nullable('true');
                $table->string('title', 1000)->nullable('true');
                $table->string('issue', 2000)->nullable('true');
                $table->string('purpose')->nullable('true');
                $table->string('monitoring', 1000)->nullable('true');
                $table->string('bias')->nullable('true');
                $table->string('accountability', 500)->nullable('true');
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
        Schema::dropIfExists('context_issues');
    }
}
