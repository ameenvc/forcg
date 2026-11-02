<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintRootCauseCorrectiveActionDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaint_root_cause_corrective_action_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('complaint_id');
            $table->longText('root_cause')->nullable();
            $table->longText('corrective_action')->nullable();
            $table->string('department')->nullable();
            $table->string('responsibility')->nullable();
            $table->string('target_date')->nullable();
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
        Schema::dropIfExists('complaint_root_cause_corrective_action_details');
    }
}
