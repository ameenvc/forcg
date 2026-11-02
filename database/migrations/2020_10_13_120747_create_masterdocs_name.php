<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMasterdocsName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('masterdocs')) {
            Schema::create('masterdocs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('document', 5000)->nullable();
                $table->string('status')->default('-')->nullable();
                $table->string('hod')->nullable();
                $table->string('docnumber')->nullable();
                $table->string('type')->nullable();
                $table->integer('level')->nullable();
                $table->string('revnumber', 1000)->default('-')->nullable();
                $table->string('nrevnumber')->nullable();
                $table->date('erevdate')->nullable();
                $table->date('nrevdate')->nullable();
                $table->string('department')->nullable();
                $table->string('reviewedat')->nullable();
                $table->string('postedby')->nullable();
                $table->string('affected', 2000)->nullable();
                $table->string('reason', 2000)->nullable();
                $table->string('ch_approved')->nullable();
                $table->string('changedate')->nullable();
                $table->string('removedate')->nullable();
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
        Schema::dropIfExists('masterdocs');
    }
}
