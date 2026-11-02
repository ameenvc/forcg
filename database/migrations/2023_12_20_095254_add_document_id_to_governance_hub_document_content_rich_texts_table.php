<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDocumentIdToGovernanceHubDocumentContentRichTextsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('governance_hub_document_content_rich_texts', function (Blueprint $table) {
            $table->unsignedbigInteger('document_id')->after('rich_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('governance_hub_document_content_rich_texts', function (Blueprint $table) {
            $table->dropColumn('document_id');
        });
    }
}
