<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateValueOfModuleClosedPageDetailsTable extends Migration
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
                'column_name'     => 'responsibility',
                'is_active'       => 1,
            ],
            [
                'module_name'     => 'corrective',
                'column_name'     => 'due date',
                'is_active'       => 1,
            ]
        ];

        foreach ($default_values as $value) {
            \App\ModuleClosedPageDetail::where('module_name', $value['module_name'])
                ->where('column_name', $value['column_name'])
                ->update([
                    'is_active'       => $value['is_active'],
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
                'column_name'     => 'responsibility',
                'is_active'       => 0,
            ],
            [
                'module_name'     => 'corrective',
                'column_name'     => 'due date',
                'is_active'       => 0,
            ]
        ];

        foreach ($default_values as $value) {
            \App\ModuleClosedPageDetail::where('module_name', $value['module_name'])
                ->where('column_name', $value['column_name'])
                ->update([
                    'is_active'       => $value['is_active'],
                    'updated_at'      => now(),
                ]);
        }
    }
}
