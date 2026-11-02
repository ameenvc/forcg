<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorhistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('vendorhistory')) {
            Schema::create('vendorhistory', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->Integer('vid');
                $table->string('type')->nullable();
                $table->Integer('proc_score')->nullable();
                $table->string('proc_id')->nullable();
                $table->string('proc_eval_at')->nullable();
                $table->Integer('proc_tot')->nullable();
                $table->Integer('hse_score')->nullable();
                $table->string('hse_id')->nullable();
                $table->string('hse_eval_at')->nullable();
                $table->Integer('hse_tot')->nullable();
                $table->Integer('enduser_score')->nullable();
                $table->string('enduser_id')->nullable();
                $table->string('enduser_eval_at')->nullable();
                $table->Integer('enduser_tot')->nullable();
                $table->Integer('total_score')->nullable();
                $table->Integer('outofscore')->nullable();
                $table->decimal('percentage')->nullable();
                $table->string('date')->nullable();
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
        Schema::dropIfExists('vendorhistory');
    }
}
