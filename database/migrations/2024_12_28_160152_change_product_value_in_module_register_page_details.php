<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\ModuleRegisterPageDetail;

class ChangeProductValueInModuleRegisterPageDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('module_register_page_details', function (Blueprint $table) {
            ModuleRegisterPageDetail::where('module_name', 'complaint')
                                   ->where('column_name', 'product')
                                   ->update([
                                              'is_active' => false,
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
            ModuleRegisterPageDetail::where('module_name', 'complaint')
                                   ->where('column_name', 'product')
                                   ->update([
                                              'is_active' => true,
                                            ]);
       });
    }
}
