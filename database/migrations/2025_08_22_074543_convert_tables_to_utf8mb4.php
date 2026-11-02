<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;
class ConvertTablesToUtf8mb4 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try{
            $dbName = DB::connection()->getDatabaseName();
            // 1. Ensure database default collation is utf8mb4_unicode_ci
            $currentCollation = DB::table('information_schema.SCHEMATA')
                ->where('SCHEMA_NAME', $dbName)
                ->value('DEFAULT_COLLATION_NAME');

            if ($currentCollation !== 'utf8mb4_unicode_ci') {
                DB::statement("ALTER DATABASE `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                Log::info("Database `$dbName` collation updated to utf8mb4_unicode_ci");
            } else {
                Log::info("Database `$dbName` already using utf8mb4_unicode_ci");
            }

            // 2. Find all tables not using utf8mb4_unicode_ci
            $tables = DB::select("
                SELECT TABLE_NAME 
                FROM information_schema.TABLES
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_COLLATION <> 'utf8mb4_unicode_ci'
                AND TABLE_NAME = 'opportunity'
            ");

            foreach ($tables as $table) {
                $tableName = $table->TABLE_NAME;
                DB::statement("ALTER TABLE `$tableName` ROW_FORMAT=DYNAMIC");
                Log::info("Updated `$tableName` to ROW_FORMAT=DYNAMIC");

                // 3. Ensure columns also match
                $columns = DB::select("
                    SELECT COLUMN_NAME, COLUMN_TYPE, IS_NULLABLE, COLUMN_DEFAULT, EXTRA
                    FROM information_schema.COLUMNS
                    WHERE TABLE_SCHEMA = DATABASE()
                    AND TABLE_NAME = ?
                    AND COLLATION_NAME IS NOT NULL
                    AND COLLATION_NAME <> 'utf8mb4_unicode_ci'
                ", [$tableName]);

                foreach ($columns as $col) {
                    $colType = strtolower($col->COLUMN_TYPE);

                    // If too big, fallback to TEXT
                    if (preg_match('/varchar\((\d+)\)/', $colType, $m)) {
                        $length = (int) $m[1];

                        if ($length > 255) {
                            // If the column is indexed, force VARCHAR(191) instead of TEXT
                            $isIndexed = DB::selectOne("
                                SELECT 1
                                FROM information_schema.STATISTICS
                                WHERE TABLE_SCHEMA = DATABASE()
                                AND TABLE_NAME = ?
                                AND COLUMN_NAME = ?
                                LIMIT 1
                            ", [$tableName, $col->COLUMN_NAME]);

                            if ($isIndexed) {
                                $newType = 'VARCHAR(255)'; // Safe for utf8mb4 + index
                            } else {
                                // Choose TEXT/MEDIUMTEXT depending on size
                                if ($length <= 65535 / 4) {
                                    $newType = 'TEXT';
                                } elseif ($length <= 16777215 / 4) {
                                    $newType = 'MEDIUMTEXT';
                                } else {
                                    $newType = 'LONGTEXT';
                                }
                            }
                        } else {
                            $newType = $col->COLUMN_TYPE;
                        }
                    } else {
                        $newType = $col->COLUMN_TYPE;
                    }
                    $null = $col->IS_NULLABLE === 'YES' ? 'NULL' : 'NOT NULL';
                    $default = '';
                    if ($col->COLUMN_DEFAULT !== null && stripos($newType, 'text') === false && stripos($newType, 'blob') === false) {
                        $default = "DEFAULT '" . addslashes($col->COLUMN_DEFAULT) . "'";
                    }
                    $extra = $col->EXTRA ?? '';

                    $sql = "ALTER TABLE `$tableName` 
                            MODIFY `$col->COLUMN_NAME` $newType 
                            CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci 
                            $null $default $extra";

                    DB::statement($sql);
                    Log::info("Converted column `$tableName.$col->COLUMN_NAME` to utf8mb4_unicode_ci");
                }
                DB::statement("ALTER TABLE `$tableName` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                Log::info("Set default charset for `$tableName` to utf8mb4_unicode_ci");
            }
            Log::info("Migration complete: all tables/columns now utf8mb4_unicode_ci");
        }catch(\Exception $e){
            Log::error("Migration failed: " . $e->getMessage());
        }
    }
}