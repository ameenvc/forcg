<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorNcrConfigurationFieldsSeeder extends Seeder
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
                'field_name' => 'vendor_name',
                'field_type' => 'dropdown',
                'display_label' => 'Vendor Name',
            ],
            [
                'field_name' => 'item_serial_number',
                'field_type' => 'text',
                'display_label' => 'Item Serial Number',
            ],
            [
                'field_name' => 'item_description',
                'field_type' => 'textarea',
                'display_label' => 'Item Description',
            ],
            [
                'field_name' => 'purchase_order_number',
                'field_type' => 'text',
                'display_label' => 'Purchase Order (PO) Number',
            ],
            [
                'field_name' => 'receiving_report_number',
                'field_type' => 'text',
                'display_label' => 'Receiving Report (RR) Number',
            ],
            [
                'field_name' => 'delivery_date',
                'field_type' => 'date',
                'display_label' => 'Delivery Date',
            ],
            [
                'field_name' => 'quantity_affected',
                'field_type' => 'number',
                'display_label' => 'Quantity Affected',
            ]
        ];
        foreach ($datas as $data) {
            if (
                !DB::table('vendor_ncr_configuration_fields')->where('field_name', $data['field_name'])
                ->where('field_type', $data['field_type'])->where('display_label', $data['display_label'])
                ->where('deleted_at', null)->exists()
            ) {
                DB::table('vendor_ncr_configuration_fields')->insert([
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
