<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableRiskhistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('riskhistory', function (Blueprint $table) {
            $table->string('impa_updated',50)->nullable()->after('impa');
            $table->string('like_updated',50)->nullable()->after('like');
            $table->string('rank_updated',50)->nullable()->after('rank');
            $table->text('mitigating_action_updated')->nullable()->after('mitigating_action');
            $table->string('action',50)->nullable()->after('mitigating_action_updated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('riskhistory', function (Blueprint $table) {
            $table->dropColumn('impa_updated');
            $table->dropColumn('like_updated');
            $table->dropColumn('rank_updated');
            $table->dropColumn('mitigating_action_updated');
            $table->dropColumn('action');
        });
    }
}
