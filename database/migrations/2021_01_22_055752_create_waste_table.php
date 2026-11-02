<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWasteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('waste')) {
            Schema::create('waste', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('department')->nullable();
                $table->date('date')->nullable();
                $table->string('type')->nullable();
                $table->string('itemname')->nullable();
                $table->string('wastedesc', 2000)->nullable();
                $table->string('unit')->nullable();
                $table->decimal('quantity')->nullable();
                $table->string('useableparts', 1000)->nullable();
                $table->string('posted_by')->nullable();
                $table->string('posted_name')->nullable();
                $table->string('posted_at')->nullable();
                $table->string('reviewer', 1500)->nullable();
                $table->string('revcheck', 1500)->nullable();
                $table->string('approver')->nullable();
                $table->Integer('count')->nullable();
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
        Schema::dropIfExists('waste');
    }
}
