<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompletedToCourseHeadingContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_heading_contents', function (Blueprint $table) {
            $table->boolean('completed')->after('duration')->default(false);
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
            $table->dropColumn('completed');
        });
    }
}
