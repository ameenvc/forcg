<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintEscalationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaint_escalations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('complaint_id')->index();
            $table->timestamp('first_escalation_sent_at')->nullable();
            $table->timestamp('last_escalation_sent_at')->nullable();
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
        Schema::dropIfExists('complaint_escalations');
    }
}
