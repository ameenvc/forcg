<?php

use App\ModuleClosedPageDetail;
use Illuminate\Database\Migrations\Migration;

class UpdatePositionValueOfModuleClosedPageDetailsTable extends Migration
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
                'column_name'     => 'type',
                'column_position' => 4,
                'is_active'       => 1,
            ],
            [
                'module_name'     => 'corrective',
                'column_name'     => 'category',
                'column_position' => 5,
                'is_active'       => 1,
            ],
            [
                'module_name'     => 'corrective',
                'column_name'     => 'department',
                'column_position' => 6,
                'is_active'       => 1,
            ],
            [
                'module_name'     => 'corrective',
                'column_name'     => 'responsibility',
                'column_position' => 7,
                'is_active'       => 1,
            ],
            [
                'module_name'     => 'corrective',
                'column_name'     => 'due date',
                'column_position' => 8,
                'is_active'       => 1,
            ],
            [
                'module_name'     => 'corrective',
                'column_name'     => 'division',
                'column_position' => 9,
                'is_active'       => 0,
            ],
            [
                'module_name'     => 'corrective',
                'column_name'     => 'subdivision',
                'column_position' => 10,
                'is_active'       => 0,
            ],
            [
                'module_name'     => 'corrective',
                'column_name'     => 'raisedby',
                'column_position' => 11,
                'is_active'       => 0,
            ],
            [
                'module_name'     => 'corrective',
                'column_name'     => 'financial loss',
                'column_position' => 12,
                'is_active'       => 0,
            ],
            [
                'module_name'     => 'corrective',
                'column_name'     => 'potential cost saving',
                'column_position' => 13,
                'is_active'       => 0,
            ],
            [
                'module_name'     => 'corrective',
                'column_name'     => 'email remainder for action',
                'column_position' => 14,
                'is_active'       => 0,
            ],
        ];

        foreach ($default_values as $value) {
            \App\ModuleClosedPageDetail::where('module_name', $value['module_name'])
                ->where('column_name', $value['column_name'])
                ->update([
                    'column_position' => $value['column_position'],
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
        //
    }
}
