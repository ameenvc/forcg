<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDoctypeIdColumnToGovernanceHubDocumentContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('governance_hub_document_contents', function (Blueprint $table) {
            $table->unsignedBigInteger('doctype_id')->nullable()->after('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('governance_hub_document_contents', function (Blueprint $table) {
            $table->dropColumn('doctype_id');
        });
    }
}
