<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorStandardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_standards', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('vendors_id');
            //$table->foreign('vendors_id')->nullable()->references('id')->on('vendor')->onDelete('cascade');

            $table->unsignedBigInteger('standards_id');
            //$table->foreign('standards_id')->nullable()->references('id')->on('standards')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_iso_standards');
    }
}
