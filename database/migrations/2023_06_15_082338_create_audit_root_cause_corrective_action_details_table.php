<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditRootCauseCorrectiveActionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_root_cause_corrective_action_details', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('root_cause');
            $table->text('corrective_action');
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
        Schema::dropIfExists('audit_root_cause_corrective_action_details');
    }
}
