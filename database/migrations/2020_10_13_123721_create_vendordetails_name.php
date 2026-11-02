<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendordetailsName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('vendordetails')) {
            Schema::create('vendordetails', function (Blueprint $table) {
                $table->id();
                $table->string('question');
                $table->integer('score')->nullable();
                $table->string('type');
                $table->string('filled_by')->nullable();
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
        Schema::dropIfExists('vendordetails');
    }
}
