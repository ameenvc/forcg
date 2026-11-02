<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGovernanceHubDraftsReviewerDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('governance_hub_drafts_reviewer_details', function (Blueprint $table) {
            $table->id();
			$table->unsignedbigInteger('draft_id');
			$table->unsignedbigInteger('reviewer_id');
			$table->boolean('reviewed_status')->default(false);
			$table->date('reviewed_date')->nullable();
			$table->boolean('draft_rejected')->default(false);
			$table->longText('draft_reject_reason')->nullable();
			$table->unsignedbigInteger('new_governance_hub_id')->nullable();
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
        Schema::dropIfExists('governance_hub_drafts_reviewer_details');
    }
}
