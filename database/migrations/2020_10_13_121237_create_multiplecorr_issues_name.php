<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMultiplecorrIssuesName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('multiplecorr_issues')) {
            Schema::create('multiplecorr_issues', function (Blueprint $table) {
                $table->bigIncrements('Sno');
                $table->integer('id')->nullable();
                $table->string('issue_file', 2000)->nullable();
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
        Schema::dropIfExists('multiplecorr_issues');
    }
}
