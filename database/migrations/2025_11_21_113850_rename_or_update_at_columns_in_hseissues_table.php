<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class RenameOrUpdateAtColumnsInHseissuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function(){
            Schema::table('hseissues', function (Blueprint $table) {

                // Case 1: update_at exists, last_reviewed_at does NOT
                if (
                    Schema::hasColumn('hseissues', 'update_at') &&
                    !Schema::hasColumn('hseissues', 'last_reviewed_at')
                ) {
                    $table->renameColumn('update_at', 'last_reviewed_at');
                }

                // Case 2: neither exists → add last_reviewed_at
                if (
                    !Schema::hasColumn('hseissues', 'update_at') &&
                    !Schema::hasColumn('hseissues', 'last_reviewed_at')
                ) {
                    $table->timestamp('last_reviewed_at')->nullable();
                }
            });

            // Case 3: update_at still exists → remove it
            if (Schema::hasColumn('hseissues', 'update_at')) {
                Schema::table('hseissues', function (Blueprint $table) {
                    $table->dropColumn('update_at');
                });
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::transaction(function(){
            if (!Schema::hasColumn('hseissues', 'update_at')) {
                Schema::table('hseissues', function (Blueprint $table) {
                    $table->timestamp('update_at')->nullable();
                });
            }
            if (Schema::hasColumn('hseissues', 'last_reviewed_at')) {
                Schema::table('hseissues', function (Blueprint $table) {
                    $table->renameColumn('last_reviewed_at', 'update_at');
                });
            }
        });
    }
}
