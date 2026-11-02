<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorNcrConfigurationAddedFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_ncr_configuration_added_fields', function (Blueprint $table) {
            $table->id();
            $table->string('field_name')->nullable();
            $table->string('field_type')->nullable();
            $table->string('display_label')->nullable();
            $table->string('field_label_version')->default('1');
            $table->boolean('is_mandatory')->default(false);
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->dateTime('modified_at')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('field_id')->nullable();
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
        Schema::dropIfExists('vendor_ncr_configuration_added_fields');
    }
}
