<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpportunityName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('opportunity')) {
            Schema::create('opportunity', function (Blueprint $table) {
                $table->bigIncrements('Oid');
                $table->integer('count')->default('0')->nullable();
                $table->string('dep_id')->nullable();
                $table->string('department');
                $table->string('oppname');
                $table->string('oppdes', 2000);
                $table->date('tardate')->nullable();
                $table->string('ohs')->nullable();
                $table->string('type');
                $table->string('action', 2000)->nullable();
                $table->string('responsibility')->nullable();
                $table->string('likelihood');
                $table->string('benefit');
                $table->string('impact')->nullable();
                $table->string('status')->default('Review Pending');
                $table->string('actionstatus')->nullable();
                $table->string('postedby')->nullable();
                $table->string('createdat')->nullable();
                $table->timeStamp('updatedat');
                $table->string('reviewdate')->nullable();
                $table->string('reason')->nullable();
                $table->text('remarks')->nullable();
                $table->string('oppstatus')->default('Not achieved');
                $table->string('secondary')->nullable();
                $table->string('ndareason')->default('NULL');
                $table->integer('monthly')->default('0');
                $table->integer('twodays')->default('0');
                $table->integer('due')->default('0');
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
        Schema::dropIfExists('opportunity');
    }
}
