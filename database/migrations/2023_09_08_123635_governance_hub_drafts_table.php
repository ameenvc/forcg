<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GovernanceHubDraftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('governance_hub_drafts', function (Blueprint $table) {
            $table->id();
            $table->string('drafted_by')->nullable('true');
            $table->string('document_name')->nullable();
            $table->string('type')->nullable();
            $table->date('date')->nullable();
            $table->string('owner')->nullable();
            $table->string('status')->nullable('true');
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
        Schema::dropIfExists('governance_hub_drafts');
    }
}
