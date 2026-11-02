<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyUniqueKeyInGovernanceHubDocumentContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('governance_hub_document_contents', function (Blueprint $table) {
            $table->dropUnique('governance_hub_document_contents_title_unique');
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
            $table->unique('title')->after('document_id');
        });
    }
}
