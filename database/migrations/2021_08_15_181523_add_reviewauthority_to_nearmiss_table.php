<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReviewauthorityToNearmissTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nearmiss', function (Blueprint $table) {
            $table->string('reviewauthority')->after('area')->nullable();
            $table->string('closureauthority')->after('area')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nearmiss', function (Blueprint $table) {
            //
        });
    }
}
