<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FixOpportunityTableDefaultValues extends Migration
{
    public function up()
    {
        try {
            $table = 'opportunity';

            // Fix the columns that got double/triple quoted defaults
            $fixes = [
                'type'          => "'NULL'",
                'responsibility'=> "'NULL'",
                'impact'        => "'NULL'",
                'status'        => "'Review Pending'",
                'oppstatus'     => "'Not achieved'",
                'dep_id'        => 'NULL',
                'ohs'           => 'NULL',
                'actionstatus'  => 'NULL',
                'postedby'      => 'NULL',
                'createdat'     => 'NULL',
                'reviewdate'    => 'NULL',
                'secondary'     => 'NULL',
                'file'          => 'NULL',
            ];

            foreach ($fixes as $col => $default) {
                // Build SQL safely
                $sql = "ALTER TABLE `$table` 
                        ALTER `$col` SET DEFAULT $default";
                DB::statement($sql);
                Log::info("Fixed default for `$table.$col` to $default");
            }

            // Special case: ndareason was varchar(2000) -> text, lost default
            // If you need to restore a default 'NULL' string:
            DB::statement("ALTER TABLE `$table` 
                           MODIFY `ndareason` TEXT NOT NULL DEFAULT 'NULL'
                           CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            Log::info("Fixed column `$table.ndareason` to restore default 'NULL'");

            Log::info("Migration complete: defaults fixed for `$table`");
        } catch (\Exception $e) {
            Log::error("Fix migration failed: " . $e->getMessage());
        }
    }
}
