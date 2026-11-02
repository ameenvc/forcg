<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseHeadingContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_heading_contents', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('course_headings_id');

            $table->string('name');

            $table->string('file')->nullable();

            $table->text('rich_text')->nullable();

            $table->boolean('is_deleted')->default(false);

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
        Schema::dropIfExists('course_heading_contents');
    }
}
