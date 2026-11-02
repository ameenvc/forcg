<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApprovedByColumnInComplianceLegalDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('compliance_legal_documents', function (Blueprint $table) {
            $table->bigInteger('approved_by')->nullable()->after('modified_at');
            $table->dateTime('approved_at')->nullable()->after('approved_by');
            $table->bigInteger('rejected_by')->nullable()->after('approved_at');
            $table->dateTime('rejected_at')->nullable()->after('rejected_by');
            $table->string('token')->nullable()->after('rejected_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('compliance_legal_documents', function (Blueprint $table) {
            $table->dropColumns([
                'approved_by','approved_at','rejected_by','rejected_at','token'
            ]);
        });
    }
}
