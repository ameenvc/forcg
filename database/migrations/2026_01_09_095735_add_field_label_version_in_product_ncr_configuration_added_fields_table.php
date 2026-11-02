<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldLabelVersionInProductNcrConfigurationAddedFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_ncr_configuration_added_fields', function (Blueprint $table) {
            $table->string('field_label_version')->default('1')->after('display_label');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_ncr_configuration_added_fields', function (Blueprint $table) {
            $table->dropColumn('field_label_version');
        });
    }
}
