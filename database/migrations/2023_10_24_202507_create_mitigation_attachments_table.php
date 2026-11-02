<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMitigationAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('mitigation_attachments')) {
            Schema::create('mitigation_attachments', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('mitigation_id');
                $table->string('attachment');
                $table->timestamps();
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
        Schema::dropIfExists('mitigation_attachments');
    }
}
