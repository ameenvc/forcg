<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGovernanceHubReadRequestDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('governance_hub_read_request_details', function (Blueprint $table) {
            $table->id();
			$table->unsignedbigInteger('governance_hub_id');
			$table->unsignedbigInteger('user_id');
			$table->unsignedbigInteger('read_request_submitted_by');
			$table->date('read_request_submitted_date');
			$table->string('notification_interval');
			$table->date('notification_duedate');
			$table->boolean('review_completed')->default(false);
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
        Schema::dropIfExists('governance_hub_read_request_details');
    }
}
