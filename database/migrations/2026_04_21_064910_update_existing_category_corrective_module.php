<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateExistingCategoryCorrectiveModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            DB::table('categories')
            ->where('categoryname', 'Product Non- Conformity')
            ->update([
                'is_pnc_cat' => 'yes',
            ]);
            DB::table('categories')
            ->where('categoryname', 'Process Non- Conformity')
            ->update([
                'is_process_cat' => 'yes',
            ]);
            DB::table('categories')
            ->where('categoryname', 'Vendor Non- Conformity')
            ->update([
                'is_vendor_cat' => 'yes',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::transaction(function () {
            DB::table('categories')
                ->where('categoryname', 'Product Non- Conformity')
                ->update([
                    'is_pnc_cat' => 'no',
                ]);
            DB::table('categories')
                ->where('categoryname', 'Process Non- Conformity')
                ->update([
                    'is_process_cat' => 'no',
                ]);
            DB::table('categories')
                ->where('categoryname', 'Vendor Non- Conformity')
                ->update([
                    'is_vendor_cat' => 'no',
                ]);
        });
    }
}
