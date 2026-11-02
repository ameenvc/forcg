<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOhsName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('ohs')) {
            Schema::create('ohs', function (Blueprint $table) {
                $table->bigIncrements('Rid');
                $table->integer('count')->default('0');
                $table->string('actid')->nullable();
                $table->string('risk_status')->default('Review Pending')->nullable();
                $table->string('department')->nullable();
                $table->string('type')->nullable();
                $table->string('activity')->nullable();
                $table->string('legal', 500)->nullable();
                $table->string('location', 500)->nullable();
                $table->string('team', 500)->nullable();
                $table->string('proposechange', 1000)->nullable();
                $table->string('postedby')->nullable();
                $table->string('created_at')->nullable();
                $table->string('reviewed_at')->nullable();
                $table->string('approvedby')->nullable();
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
        Schema::dropIfExists('ohs');
    }
}
