<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComplaintfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('complaintfiles')) {
            Schema::create('complaintfiles', function (Blueprint $table) {
                $table->bigIncrements('sno');
                $table->integer('id')->nullable('true');
                $table->string('files', 2000)->nullable('true');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('complaintfiles');
    }
}
