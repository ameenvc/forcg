<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProcessstageValueCorrectiveInModuleRegisterPageDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('module_register_page_details', function (Blueprint $table) {
             \Illuminate\Support\Facades\DB::table('module_register_page_details')
									->where('module_name', 'corrective')
									->where('column_name', 'process stage')
									->update([
											   'is_dragdropable' => false,
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
        Schema::table('module_register_page_details', function (Blueprint $table) {
             \Illuminate\Support\Facades\DB::table('module_register_page_details')
									->where('module_name', 'corrective')
									->where('column_name', 'process stage')
									->update([
											   'is_dragdropable' => true,
											 ]);
        });
    }
}
