<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateValueOfModuleRegisterPageDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $default_values = [
            [
                'module_name'     => 'corrective',
                'column_name'     => 'correction target date',
                'column_position' => 8,
                'is_active'       => 1,
                'enabled_by_user' => 0,
            ],
            [
                'module_name'     => 'corrective',
                'column_name'     => 'due date',
                'column_position' => 25,
                'is_active'       => 0,
                'enabled_by_user' => 0,
            ],
        ];

        foreach ($default_values as $value) {
            \App\ModuleRegisterPageDetail::where('module_name', $value['module_name'])
                ->where('column_name', $value['column_name'])
                ->update([
                    'column_position' => $value['column_position'],
                    'is_active'       => $value['is_active'],
                    'enabled_by_user' => $value['enabled_by_user'],
                    'updated_at'      => now(),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $default_values = [
            [
                'module_name'     => 'corrective',
                'column_name'     => 'correction target date',
                'column_position' => 25,
                'is_active'       => 0,
                'enabled_by_user' => 0,
            ],
            [
                'module_name'     => 'corrective',
                'column_name'     => 'due date',
                'column_position' => 8,
                'is_active'       => 1,
                'enabled_by_user' => 0,
            ],
        ];

        foreach ($default_values as $value) {
            \App\ModuleRegisterPageDetail::where('module_name', $value['module_name'])
                ->where('column_name', $value['column_name'])
                ->update([
                    'column_position' => $value['column_position'],
                    'is_active'       => $value['is_active'],
                    'enabled_by_user' => $value['enabled_by_user'],
                    'updated_at'      => now(),
                ]);
        }
    }
}
