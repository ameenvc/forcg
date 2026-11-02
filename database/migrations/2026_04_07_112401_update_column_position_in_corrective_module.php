<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateColumnPositionInCorrectiveModule extends Migration
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
                'module_name' => 'corrective',
                'column_name'=>'type',
                'column_position'=>4,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'category',
                'column_position'=>5,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'department',
                'column_position'=>6,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'responsibility',
                'column_position'=>7,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'correction target date',
                'column_position'=>8,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'process stage',
                'column_position'=>9,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'action status',
                'column_position'=>10,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'raisedby',
                'column_position'=>11,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'added date',
                'column_position'=>12,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'source',
                'column_position'=>13,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'detection stage',
                'column_position'=>14,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'division',
                'column_position'=>15,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'subdivision',
                'column_position'=>16,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'investigation team lead',
                'column_position'=>17,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'investigation target date',
                'column_position'=>18,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'financial loss',
                'column_position'=>19,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'potential cost saving',
                'column_position'=>20,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'email remainder for action',
                'column_position'=>21,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'action reviewed by',
                'column_position'=>22,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'action reviewed date',
                'column_position'=>23,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'closure reviewed by',
                'column_position'=>24,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'closure reviewed date',
                'column_position'=>25,
            ],
            [
                'module_name' => 'corrective',
                'column_name'=>'due date',
                'column_position'=>26,
            ]
        ];
        DB::transaction(function () use ($default_values) {
            $time = now();
            foreach ($default_values as $value) {
                DB::table('module_register_page_details')
                    ->where('module_name', $value['module_name'])
                    ->where('column_name', $value['column_name'])
                    ->update([
                        'column_position' => $value['column_position'],
                        'updated_at' => $time,
                    ]);
                DB::table('module_closed_page_details')
                    ->where('module_name', $value['module_name'])
                    ->where('column_name', $value['column_name'])
                    ->update([
                        'column_position' => $value['column_position'],
                        'updated_at' => $time,
                    ]);
            }
        });
    }
}
