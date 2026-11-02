<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplianceLegalDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compliance_legal_documents', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('RAC_id')->nullable();
            $table->string('name')->nullable();
            $table->string('status')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('modified_by')->nullable();
            $table->dateTime('modified_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
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
        Schema::dropIfExists('compliance_legal_documents');
    }
}
