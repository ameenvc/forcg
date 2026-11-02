<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('customer')) {
            Schema::create('customer', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('customer', 2000)->nullable('true');
                $table->string('location', 2000)->nullable('true');
                $table->string('contact', 2000)->nullable('true');
                $table->string('product', 2000)->nullable('true');
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
        Schema::dropIfExists('customer');
    }
}
