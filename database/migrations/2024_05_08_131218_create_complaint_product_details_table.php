<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintProductDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaint_product_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('complaint_id')->nullable();
            $table->string('item_code')->nullable();
            $table->longText('product_description')->nullable();
            $table->string('idno')->nullable();
            $table->string('total_delivery_quantity')->nullable();
            $table->string('affected_quantity')->nullable();
            $table->string('unit')->nullable();
            $table->string('cost_impact')->nullable();
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
        Schema::dropIfExists('complaint_product_details');
    }
}
