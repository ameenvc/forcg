<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiskAiOrganizationLearningTables extends Migration
{
    public function up()
    {
        Schema::create('risk_ai_profiles', function (Blueprint $table) {
            $table->bigIncrements('id'); $table->string('organization_key', 64); $table->unsignedInteger('revision');
            $table->boolean('enabled')->default(false); $table->mediumText('content'); $table->string('source_reference', 500)->nullable();
            $table->string('created_by', 64); $table->timestamp('created_at')->nullable();
            $table->unique(['organization_key', 'revision']);
        });
        Schema::create('risk_ai_knowledge_notes', function (Blueprint $table) {
            $table->bigIncrements('id'); $table->string('organization_key', 64); $table->string('title', 190); $table->text('content');
            $table->string('keywords', 500)->nullable(); $table->string('source_reference', 500); $table->date('review_by')->nullable();
            $table->string('status', 20)->default('approved'); $table->string('created_by', 64); $table->string('withdrawn_by', 64)->nullable();
            $table->unsignedBigInteger('source_feedback_id')->nullable(); $table->timestamps(); $table->index(['organization_key', 'status']);
        });
        Schema::create('risk_ai_runs', function (Blueprint $table) {
            $table->uuid('id')->primary(); $table->string('organization_key', 64); $table->string('actor', 64); $table->string('action', 30);
            $table->string('mode', 20); $table->string('topic', 500); $table->longText('result');
            $table->unsignedInteger('profile_revision')->nullable(); $table->text('note_ids')->nullable(); $table->timestamp('created_at')->nullable();
            $table->index(['organization_key', 'actor']);
        });
        Schema::create('risk_ai_feedback', function (Blueprint $table) {
            $table->bigIncrements('id'); $table->string('organization_key', 64); $table->uuid('run_id'); $table->string('actor', 64);
            $table->string('rating', 30); $table->text('comment')->nullable(); $table->string('status', 20)->default('pending');
            $table->string('reviewed_by', 64)->nullable(); $table->timestamp('reviewed_at')->nullable(); $table->timestamps();
            $table->unique(['run_id', 'actor']); $table->index(['organization_key', 'status']);
        });
    }
    public function down()
    {
        Schema::dropIfExists('risk_ai_feedback'); Schema::dropIfExists('risk_ai_runs');
        Schema::dropIfExists('risk_ai_knowledge_notes'); Schema::dropIfExists('risk_ai_profiles');
    }
}
