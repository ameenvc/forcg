<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGovernanceHubDraftsCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('governance_hub_drafts_comments', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('parent_id');
            $table->longText('comment');
            $table->unsignedBigInteger('commentable_id');
			$table->string('commentable_type');
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
        Schema::dropIfExists('governance_hub_drafts_comments');
    }
}
