<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGovernanceHubChangeRequestDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('governance_hub_change_request_details', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('parent_governance_id');
			$table->date('change_requested_date');
			$table->unsignedBigInteger('change_requested_submitted_by_user_id');
			$table->longText('changes_made');
			$table->boolean('change_requested_completed_status')->default(false);
			$table->date('change_request_completed_date')->nullable();
			$table->string('new_revision')->nullable();
			$table->string('unique_id');
			$table->string('reviewers')->nullable();
			$table->string('approvers')->nullable();		
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
        Schema::dropIfExists('governance_hub_change_request_details');
    }
}
