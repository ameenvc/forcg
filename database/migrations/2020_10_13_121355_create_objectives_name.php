<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObjectivesName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('objectives')) {
            Schema::create('objectives', function (Blueprint $table) {
                $table->bigIncrements('Oid');
                $table->string('department')->nullable();
                $table->text('objectives')->nullable();
                $table->integer('target')->nullable();
                $table->string('span')->nullable();
                $table->date('tardate')->nullable();
                $table->text('methodology')->nullable();
                $table->text('resources')->nullable();
                $table->text('responsibility')->nullable();
                $table->date('fromdate')->nullable();
                $table->date('todate')->nullable();
                $table->string('frequency')->nullable();
                $table->string('createdat')->nullable();
                $table->decimal('trend_val1')->nullable();
                $table->decimal('trend_val2')->nullable();
                $table->decimal('trend_val3')->nullable();
                $table->decimal('trend_val4')->nullable();
                $table->decimal('trend_val5')->nullable();
                $table->string('postedby')->nullable();
                $table->string('status')->default('Review Pending')->nullable();
                $table->text('remarks')->nullable();
                $table->text('proposal')->nullable();
                $table->text('higherremarks')->nullable();
                $table->text('higherproposal')->nullable();
                $table->string('otarget', 500)->nullable();
                $table->string('oachieved', 500)->nullable();
                $table->string('frstatus')->nullable();
                $table->string('CAR')->nullable();
                $table->text('description')->nullable();
                $table->string('reviewed_at')->nullable();
                $table->string('reviewed_by')->nullable();
                $table->string('approved_at')->nullable();
                $table->string('approved_by')->nullable();
                $table->integer('count')->default('0');
                $table->string('unit')->nullable();
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
        Schema::dropIfExists('objectives');
    }
}
