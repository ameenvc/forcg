<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertOrUpdateDataToModuleRegisterPageDetailsTableCorrespondingToComplaintConfigurationData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $complaintProductColumnConfigs = [
            [
                'module_name' => 'complaint',
                'column_name'=>'S.No',
                'column_position'=>1,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'tracking number',
                'column_position'=>2,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'title',
                'column_position'=>3,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'customer',
                'column_position'=>4,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'type',
                'column_position'=>5,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'department',
                'column_position'=>6,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'responsibility',
                'column_position'=>7,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'due date',
                'column_position'=>8,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'process stage',
                'column_position'=>9,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'action status',
                'column_position'=>10,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'raisedby',
                'column_position'=>11,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'added date',
                'column_position'=>12,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'complaint received by',
                'column_position'=>13,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'complaint received date',
                'column_position'=>14,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'mode of complaint',
                'column_position'=>15,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'customer phone',
                'column_position'=>16,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'customer email',
                'column_position'=>17,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'product',
                'column_position'=>18,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'sale order no.',
                'column_position'=>19,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'delivery no.',
                'column_position'=>20,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'delivery date',
                'column_position'=>21,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'plant',
                'column_position'=>22,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'division',
                'column_position'=>23,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'subdivision',
                'column_position'=>24,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'financial loss',
                'column_position'=>25,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'potential cost saving',
                'column_position'=>26,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'email reminder for action',
                'column_position'=>27,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'investigation team lead',
                'column_position'=>28,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'investigation target date',
                'column_position'=>29,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'reviewed by',
                'column_position'=>30,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'reviewed date',
                'column_position'=>31,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'closure reviewed by',
                'column_position'=>32,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint',
                'column_name'=>'closure reviewed date',
                'column_position'=>33,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ]
        ];

        $complaintProjectColumnConfigs = [
            [
                'module_name' => 'complaint_project',
                'column_name'=>'S.No',
                'column_position'=>1,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'tracking number',
                'column_position'=>2,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'title',
                'column_position'=>3,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'customer',
                'column_position'=>4,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'type',
                'column_position'=>5,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'department',
                'column_position'=>6,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'responsibility',
                'column_position'=>7,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'due date',
                'column_position'=>8,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'process stage',
                'column_position'=>9,
                'column_filter'=>1,
                'is_active'=>1,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'action status',
                'column_position'=>10,
                'column_filter'=>0,
                'is_active'=>1,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'raisedby',
                'column_position'=>11,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'added date',
                'column_position'=>12,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'complaint received by',
                'column_position'=>13,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'complaint received date',
                'column_position'=>14,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'mode of complaint',
                'column_position'=>15,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'customer phone',
                'column_position'=>16,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'customer email',
                'column_position'=>17,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'product',
                'column_position'=>18,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'job order no.',
                'column_position'=>19,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'product serial no.',
                'column_position'=>20,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'project name',
                'column_position'=>21,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'plant',
                'column_position'=>22,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'complaint category',
                'column_position'=>23,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],

            [
                'module_name' => 'complaint_project',
                'column_name'=>'division',
                'column_position'=>24,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'subdivision',
                'column_position'=>25,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'financial loss',
                'column_position'=>26,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'potential cost saving',
                'column_position'=>27,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'email reminder for action',
                'column_position'=>28,
                'column_filter'=>0,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'investigation team lead',
                'column_position'=>29,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'investigation target date',
                'column_position'=>30,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'reviewed by',
                'column_position'=>31,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'reviewed date',
                'column_position'=>32,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'closure reviewed by',
                'column_position'=>33,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ],
            [
                'module_name' => 'complaint_project',
                'column_name'=>'closure reviewed date',
                'column_position'=>34,
                'column_filter'=>1,
                'is_active'=>0,
                'enabled_by_user'=>0,
            ]
        ];

        foreach ($complaintProductColumnConfigs as $key => $config) {
            $row = DB::table('module_register_page_details')
                ->where('module_name', $config['module_name'])
                ->where('column_name', $config['column_name'])
                ->first();

            if ($row) {
                $updateData = [];
                if ($row->column_position != $config['column_position']) {
                    $updateData['column_position'] = $config['column_position'];
                }

                if (!empty($updateData)) {
                    $updateData['updated_at'] = now();
                    DB::table('module_register_page_details')
                        ->where('id', $row->id)
                        ->update($updateData);
                }
            } else {
                DB::table('module_register_page_details')->insert($config);
            }
        }

        foreach ($complaintProjectColumnConfigs as $key => $config) {
            $row = DB::table('module_register_page_details')
                ->where('module_name', $config['module_name'])
                ->where('column_name', $config['column_name'])
                ->first();

            if ($row) {
                $updateData = [];
                if ($row->column_position != $config['column_position']) {
                    $updateData['column_position'] = $config['column_position'];
                }

                if (!empty($updateData)) {
                    $updateData['updated_at'] = now();
                    DB::table('module_register_page_details')
                        ->where('id', $row->id)
                        ->update($updateData);
                }
            } else {
                DB::table('module_register_page_details')->insert($config);
            }
        }


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('module_register_page_details_table_corresponding_to_complaint_configuration_data', function (Blueprint $table) {
            //
        });
    }
}
