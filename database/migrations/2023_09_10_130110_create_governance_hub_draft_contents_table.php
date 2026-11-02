<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGovernanceHubDraftContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('governance_hub_draft_contents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_id');
            $table->foreign('document_id')->references('id')->on('governance_hub_drafts')->onDelete('cascade');
            $table->string('title')->unique();
            $table->string('type')->nullable();
            $table->string('responsibility')->nullable();
            $table->text('rich_text')->nullable();
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
        Schema::dropIfExists('governance_hub_draft_contents');
    }
}
