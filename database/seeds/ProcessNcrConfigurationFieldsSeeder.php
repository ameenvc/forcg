<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProcessNcrConfigurationFieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas = [
            [
                'field_name' => 'job_order_number',
                'field_type' => 'text',
                'display_label' => 'Job Order Number',
            ],
            [
                'field_name' => 'product_serial_number',
                'field_type' => 'text',
                'display_label' => 'Product Serial Number',
            ],
            [
                'field_name' => 'process_name',
                'field_type' => 'text',
                'display_label' => 'Process Name',
            ],
            [
                'field_name' => 'machine_equipment_id',
                'field_type' => 'text',
                'display_label' => 'Machine / Equipment ID',
            ],
            [
                'field_name' => 'operator_shift',
                'field_type' => 'text',
                'display_label' => 'Operator / Shift',
            ],
            [
                'field_name' => 'production_line',
                'field_type' => 'text',
                'display_label' => 'Production Line',
            ],
            [
                'field_name' => 'date_of_process',
                'field_type' => 'date',
                'display_label' => 'Date of Process',
            ],
            [
                'field_name' => 'quantity_affected',
                'field_type' => 'number',
                'display_label' => 'Quantity Affected',
            ]
        ];
        foreach ($datas as $data) {
            if (
                !DB::table('process_ncr_configuration_fields')->where('field_name', $data['field_name'])
                ->where('field_type', $data['field_type'])->where('display_label', $data['display_label'])
                ->where('deleted_at', null)->exists()
            ) {
                DB::table('process_ncr_configuration_fields')->insert([
                    'field_name' => $data['field_name'],
                    'field_type' => $data['field_type'],
                    'display_label' => $data['display_label'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
