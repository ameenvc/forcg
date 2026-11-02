<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGovernanceHubDocumentReferenceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('governance_hub_document_reference_details', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('document_id');
			$table->string('reference_doc_number')->nullable();
			$table->string('reference_doc_name')->nullable();
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
        Schema::dropIfExists('governance_hub_document_reference_details');
    }
}
