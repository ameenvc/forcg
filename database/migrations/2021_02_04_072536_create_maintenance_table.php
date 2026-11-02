<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaintenanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('maintenance')) {
            Schema::create('maintenance', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('count')->default('0');
                $table->string('equid')->nullable();
                $table->string('department')->nullable();
                $table->string('desc')->nullable();
                $table->string('type')->nullable();
                $table->string('location')->nullable();
                $table->string('serial')->nullable();
                $table->string('make')->nullable();
                $table->string('testfreq')->nullable();
                $table->string('freq')->nullable();
                $table->string('addedby')->nullable();
                $table->string('addedat')->nullable();
                $table->string('readfreq')->nullable();
                $table->string('pmstatus')->nullable();
                $table->string('pmby')->nullable();
                $table->string('pmdate')->nullable();
                $table->string('schedule')->nullable();
                $table->string('condition')->nullable();
                $table->string('teststatus')->nullable();
                $table->string('testby')->nullable();
                $table->string('lasttestdate')->nullable();
                $table->string('testschedule')->nullable();
                $table->string('testcondition')->nullable();
                $table->string('testno')->nullable();
                $table->string('fault')->nullable();
                $table->string('severity')->nullable();
                $table->string('priority')->nullable();
                $table->string('breakreq')->default('NO');
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
        Schema::dropIfExists('maintenance');
    }
}
