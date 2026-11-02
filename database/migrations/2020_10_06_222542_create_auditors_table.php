<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('auditors')) {
            Schema::create('auditors', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('auditorsname', 250)->nullable('true');
                $table->string('email', 250)->nullable('true');
                $table->string('certificate', 2500)->nullable('true');
                $table->string('experience', 2000)->nullable('true');
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
        Schema::dropIfExists('auditors');
    }
}
