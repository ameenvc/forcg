<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlantIdColumnInComplaintCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('complaint_categories', function (Blueprint $table) {
            $table->string('plant_id')->nullable()->after('category_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('complaint_categories', function (Blueprint $table) {
            $table->dropColumn('plant_id');
        });
    }
}
