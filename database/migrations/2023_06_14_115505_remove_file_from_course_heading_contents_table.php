<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveFileFromCourseHeadingContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_heading_contents', function (Blueprint $table) {
            $table->dropColumn('file');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_heading_contents', function (Blueprint $table) {
            $table->string('file')->after('name')->nullable();
        });
    }
}
