<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryIdToHseissuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hseissues', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable()->after('category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hseissues', function (Blueprint $table) {
            $table->dropColumn('category_id');
        });
    }
}
