<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorresult extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('vendorresult')) {
            Schema::create('vendorresult', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('percent');
                $table->string('result');
                $table->string('comment', 1000);
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
        Schema::dropIfExists('vendorresult');
    }
}
