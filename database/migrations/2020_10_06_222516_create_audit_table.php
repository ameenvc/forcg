<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('audit')) {
            Schema::create('audit', function (Blueprint $table) {
                $table->bigIncrements('Aid');
                $table->integer('count')->default('0');
                $table->string('aud_id')->nullable('true');
                $table->string('department')->nullable('true');
                $table->string('hod')->nullable('true');
                $table->longText('scope')->nullable('true');
                $table->string('auditors')->nullable('true');
                $table->string('email')->nullable('true');
                $table->string('contact')->nullable('true');
                $table->year('year');
                $table->date('date')->nullable('true');
                $table->time('time')->nullable('true');
                $table->date('enddate')->nullable('true');
                $table->longText('focus')->nullable('true');
                $table->string('budgeted')->nullable('true');
                $table->string('quarter')->nullable('true');
                $table->string('status')->nullable('true');
                $table->longText('reason')->nullable('true');
                $table->string('observations')->nullable('true');
                $table->string('ncr')->nullable('true');
                $table->integer('high')->nullable('true');
                $table->integer('moderate')->nullable('true');
                $table->integer('low')->nullable('true');
                $table->string('type')->nullable('true');
                $table->string('ncrstatus')->nullable('true');
                $table->string('planned')->default('YES');
                $table->string('completed')->nullable('true');
                $table->string('archived')->default('NO');
                $table->string('acceptedby')->nullable('true');
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
        Schema::dropIfExists('audit');
    }
}
