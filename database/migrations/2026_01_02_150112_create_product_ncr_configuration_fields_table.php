<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductNcrConfigurationFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_ncr_configuration_fields', function (Blueprint $table) {
            $table->id();
            $table->string('field_name')->nullable();
            $table->string('field_type')->nullable();
            $table->string('display_label')->nullable();
            $table->boolean('is_mandatory')->nullable()->default(false);
            $table->dateTime('deleted_at')->nullable();
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
        Schema::dropIfExists('product_ncr_configuration_fields');
    }
}
