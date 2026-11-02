<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColumnNameOnModuleRegisterPageDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::table('module_register_page_details')
            ->where('module_name', 'audit')
            ->where('column_name', 'issue closed by')
            ->update([
                'column_name' => 'closure reviewed by',
            ]);
        \Illuminate\Support\Facades\DB::table('module_register_page_details')
            ->where('module_name', 'audit')
            ->where('column_name', 'issue closed date')
            ->update([
                'column_name' => 'closure reviewed date',
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Illuminate\Support\Facades\DB::table('module_register_page_details')
            ->where('module_name', 'audit')
            ->where('column_name', 'closure reviewed by')
            ->update([
                'column_name' => 'issue closed by',
            ]);
        \Illuminate\Support\Facades\DB::table('module_register_page_details')
            ->where('module_name', 'audit')
            ->where('column_name', 'closure reviewed date')
            ->update([
                'column_name' => 'issue closed date',
            ]);
    }
}
