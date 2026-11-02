<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class RemoveDueDateValueOfCorrectiveFromModuleRegisterPageDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\ModuleRegisterPageDetail::where('module_name', 'corrective')
            ->where('column_name', 'due date')
            ->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $time = date("Y-m-d H:i:s");
        $default_values = [
            [
                'module_name' => 'corrective',
                'column_name'=>'due date',
                'column_position'=>25,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];
        foreach ($default_values as $value) {
            $found = DB::table('module_register_page_details')
                ->where('module_name', $value['module_name'])
                ->where('column_name', $value['column_name'])
                ->first();
            if (!$found) {
                DB::table('module_register_page_details')->insert([
                    'module_name' => $value['module_name'],
                    'column_name' => $value['column_name'],
                    'column_position' => $value['column_position'],
                    'column_filter' => $value['column_filter'],
                    'is_active' => $value['is_active'],
                    'enabled_by_user' => $value['enabled_by_user'],
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }
    }
}
