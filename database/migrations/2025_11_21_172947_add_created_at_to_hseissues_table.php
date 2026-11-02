<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddCreatedAtToHseissuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hseissues', function (Blueprint $table) {

            // Add created_at if missing
            if (!Schema::hasColumn('hseissues', 'created_at')) {
                $table->timestamp('created_at')->nullable()->after('remarks');
            }

            // If updated_at exists but not TIMESTAMP, rename it
            if (Schema::hasColumn('hseissues', 'updated_at')) {

                $columnType = DB::getSchemaBuilder()
                    ->getColumnType('hseissues', 'updated_at');

                // If NOT mysql timestamp → convert it
                if (!in_array($columnType, ['timestamp', 'datetime'])) {
                    $table->renameColumn('updated_at', 'updated_at_old');
                }
            }
        });

        // Convert only if renamed
        if (Schema::hasColumn('hseissues', 'updated_at_old')) {

            // Add correct TIMESTAMP column
            DB::statement("
                ALTER TABLE hseissues 
                ADD COLUMN updated_at TIMESTAMP NULL AFTER created_at
            ");

            // SAFE conversion (avoids empty strings / bad dates)
            DB::statement("
                UPDATE hseissues
                SET updated_at =
                    CASE
                        WHEN updated_at_old IS NULL OR updated_at_old = '' THEN NULL
                        WHEN STR_TO_DATE(updated_at_old, '%Y-%m-%d %H:%i:%s') IS NOT NULL
                            THEN STR_TO_DATE(updated_at_old, '%Y-%m-%d %H:%i:%s')
                        ELSE NULL
                    END
            ");

            Schema::table('hseissues', function (Blueprint $table) {
                $table->dropColumn('updated_at_old');
            });
        }
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hseissues', function (Blueprint $table) {

            if (Schema::hasColumn('hseissues', 'created_at')) {
                $table->dropColumn('created_at');
            }

            if (Schema::hasColumn('hseissues', 'updated_at')) {
                $table->dropColumn('updated_at');
            }
        });
    }
}
