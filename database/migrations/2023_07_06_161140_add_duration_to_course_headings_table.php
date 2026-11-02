<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDurationToCourseHeadingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_headings', function (Blueprint $table) {
            $table->integer('duration')->after('heading')->default(0);//duration in seconds
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_headings', function (Blueprint $table) {
            $table->dropColumn('duration');
        });
    }
}
