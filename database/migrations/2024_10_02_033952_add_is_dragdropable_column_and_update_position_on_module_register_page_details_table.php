<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsDragdropableColumnAndUpdatePositionOnModuleRegisterPageDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('module_register_page_details', function (Blueprint $table) {
            $table->boolean('is_dragdropable')->default(true)->after('is_active');
        });
        \Illuminate\Support\Facades\DB::table('module_register_page_details')
            ->where('module_name', 'audit')
            ->where('column_name', 'slno')
            ->update([
                'column_position' => 1,
                'is_dragdropable' => false
            ]);
        \Illuminate\Support\Facades\DB::table('module_register_page_details')
            ->where('module_name', 'audit')
            ->where('column_name', 'id')
            ->update([
                'column_position' => 2,
                'is_dragdropable' => false
            ]);
        \Illuminate\Support\Facades\DB::table('module_register_page_details')
            ->where('module_name', 'audit')
            ->where('column_name', 'title')
            ->update([
                'column_position' => 3,
                'is_dragdropable' => false
            ]);
        \Illuminate\Support\Facades\DB::table('module_register_page_details')
            ->where('module_name', 'audit')
            ->where('column_name', 'type')
            ->update([
                'column_position' => 4
            ]);
        \Illuminate\Support\Facades\DB::table('module_register_page_details')
            ->where('module_name', 'audit')
            ->where('column_name', 'department')
            ->update([
                'column_position' => 5
            ]);
        \Illuminate\Support\Facades\DB::table('module_register_page_details')
            ->where('module_name', 'audit')
            ->where('column_name', 'responsibility')
            ->update([
                'column_position' => 6
            ]);
        \Illuminate\Support\Facades\DB::table('module_register_page_details')
            ->where('module_name', 'audit')
            ->where('column_name', 'due date')
            ->update([
                'column_position' => 7
            ]);
        \Illuminate\Support\Facades\DB::table('module_register_page_details')
            ->where('module_name', 'audit')
            ->where('column_name', 'process stage')
            ->update([
                'column_position' => 8,
                'is_dragdropable' => false
            ]);
        \Illuminate\Support\Facades\DB::table('module_register_page_details')
            ->where('module_name', 'audit')
            ->where('column_name', 'action status')
            ->update([
                'column_position' => 9,
                'is_dragdropable' => false
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('module_register_page_details', function (Blueprint $table) {
            $table->dropColumn('is_dragdropable');
        });
    }
}
