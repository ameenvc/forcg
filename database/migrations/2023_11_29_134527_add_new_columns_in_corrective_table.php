<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsInCorrectiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('corrective', function (Blueprint $table) {
            $table->enum('occurrence', ['one_time', 'regularly'])->nullable()->after('observation');
            $table->text('system_related_problems')->nullable()->after('observation');
            $table->boolean('recent_changes')->default(false)->after('observation');
            $table->boolean('follow_procedures')->default(false)->after('observation');
            $table->text('contributing_factors')->nullable()->after('observation');
            $table->text('external_factors')->nullable()->after('observation');
            $table->boolean('issue_foreseeable')->default(false)->after('observation');
            $table->text('prevent_reason')->nullable()->after('observation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('corrective', function (Blueprint $table) {
            $table->dropColumn('occurrence');
            $table->dropColumn('system_related_problems');
            $table->dropColumn('recent_changes');
            $table->dropColumn('follow_procedures');
            $table->dropColumn('contributing_factors');
            $table->dropColumn('external_factors');
            $table->dropColumn('issue_foreseeable');
            $table->dropColumn('prevent_reason');
        });
    }
}
