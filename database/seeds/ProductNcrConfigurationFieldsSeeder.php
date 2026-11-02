<?php

use Illuminate\Database\Seeder;

class ProductNcrConfigurationFieldsSeeder extends Seeder
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
                'field_name' => 'product_name',
                'field_type' => 'text',
                'display_label' => 'Product Name',
            ],
            [
                'field_name' => 'product_code',
                'field_type' => 'text',
                'display_label' => 'Product Code',
            ],
            [
                'field_name' => 'batch',
                'field_type' => 'text',
                'display_label' => 'Batch',
            ],
            [
                'field_name' => 'production_line',
                'field_type' => 'text',
                'display_label' => 'Production Line',
            ],
            [
                'field_name' => 'date_of_manufacture',
                'field_type' => 'date',
                'display_label' => 'Date of Manufacture',
            ],
            [
                'field_name' => 'quantity_affected',
                'field_type' => 'number',
                'display_label' => 'Quantity Affected',
            ]
        ];
        foreach ($datas as $data) {
            if (
                !DB::table('product_ncr_configuration_fields')->where('field_name', $data['field_name'])
                ->where('field_type', $data['field_type'])->where('display_label', $data['display_label'])
                ->where('deleted_at', null)->exists()
            ) {
                DB::table('product_ncr_configuration_fields')->insert([
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
