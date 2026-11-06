<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateEsgEvidenceTables extends Migration
{
    public function up()
    {
        Schema::create('esg_evidence', function(Blueprint $t){
            $t->bigIncrements('id'); $t->string('fingerprint',64)->unique(); $t->string('kind',20); $t->string('title',190);
            $t->string('site',190); $t->date('period_start'); $t->date('period_end'); $t->string('source_reference',500);
            $t->text('payload'); $t->string('file_path',500); $t->string('file_hash',64); $t->string('submitted_by',64);
            $t->string('status',20)->default('pending'); $t->string('reviewed_by',64)->nullable(); $t->timestamp('reviewed_at')->nullable();
            $t->text('review_note')->nullable(); $t->string('assurance_provider',190)->nullable(); $t->string('assurance_reference',500)->nullable();
            $t->string('assurance_path',500)->nullable(); $t->timestamps(); $t->index(['status','period_start','period_end']);
        });
        Schema::create('esg_evidence_events', function(Blueprint $t){
            $t->bigIncrements('id'); $t->unsignedBigInteger('evidence_id'); $t->string('actor',64); $t->string('event',30); $t->text('note')->nullable(); $t->timestamp('created_at'); $t->index('evidence_id');
        });
    }
    public function down(){Schema::dropIfExists('esg_evidence_events');Schema::dropIfExists('esg_evidence');}
}
