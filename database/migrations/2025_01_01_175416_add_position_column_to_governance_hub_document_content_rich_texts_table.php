<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddPositionColumnToGovernanceHubDocumentContentRichTextsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('governance_hub_document_content_rich_texts', function (Blueprint $table) {
            $table->integer('position')->default(1)->after('is_deleted');
        });
        if (Schema::hasColumn('governance_hub_document_content_rich_texts', 'position')) {
            DB::table('governance_hub_document_content_rich_texts')->update(['position' => DB::raw('id')]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('governance_hub_document_content_rich_texts', function (Blueprint $table) {
            $table->dropColumn('position');
        });
    }
}
