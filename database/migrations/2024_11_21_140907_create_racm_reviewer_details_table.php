<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRacmReviewerDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('racm_reviewer_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedbigInteger('racm_id')->nullable();
			$table->unsignedbigInteger('reviewer_id')->nullable();
			$table->boolean('reviewed_status')->default(false);
			$table->date('reviewed_date')->nullable();
			$table->boolean('racm_rejected')->default(false);
			$table->longText('racm_reject_reason')->nullable();
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
        Schema::dropIfExists('racm_reviewer_details');
    }
}
