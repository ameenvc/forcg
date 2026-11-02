<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\ModuleRegisterPageDetail;

class ChangeColumnPositionForComplaintInModuleRegisterPageDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('module_register_page_details', function (Blueprint $table) {
            $columns = [
                'customer' => 5,
                'product' => 9,
                'type' => 4,
                'department' => 6,
                'responsibility' => 7,
                'due date' => 8,
            ];

            foreach ($columns as $column => $position) {
                ModuleRegisterPageDetail::where('module_name', 'complaint')
                                        ->where('column_name', $column)
                                        ->update(['column_position' => $position]);
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
        Schema::table('module_register_page_details', function (Blueprint $table) {
            $columns = [
                'customer' => 4,
                'product' => 5,
                'type' => 6,
                'department' => 7,
                'responsibility' => 8,
                'due date' => 9,
            ];

            foreach ($columns as $column => $position) {
                ModuleRegisterPageDetail::where('module_name', 'complaint')
                                        ->where('column_name', $column)
                                        ->update(['column_position' => $position]);
            }
        });
    }
}
