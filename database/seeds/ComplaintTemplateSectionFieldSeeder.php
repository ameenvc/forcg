\<?php

use Illuminate\Database\Seeder;

class ComplaintTemplateSectionFieldSeeder extends Seeder
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
                'complaint_template_section_id' => 1,
                'enable_field' => 1,
                'field_name' => 'customer',
                'field_type' => 'dropdown',
                'default_display_label' => 'Customer Name',
                'field_display_label' => 'Customer Name',
                'is_multiple' => 0,
                'field_is_mandatory' => 1,
                'updated_by' => 0,
                'order' => 1
            ],
            [
                'complaint_template_section_id' => 1,
                'enable_field' => 1,
                'field_name' => 'customerphone',
                'field_type' => 'number',
                'default_display_label' => 'Customer Phone',
                'field_display_label' => 'Customer Phone',
                'is_multiple' => 0,
                'field_is_mandatory' => 0,
                'updated_by' => 0,
                'order' => 2
            ],
            [
                'complaint_template_section_id' => 1,
                'enable_field' => 1,
                'field_name' => 'customeremail',
                'field_type' => 'email',
                'default_display_label' => 'Customer Email',
                'field_display_label' => 'Customer Email',
                'is_multiple' => 0,
                'field_is_mandatory' => 1,
                'updated_by' => 0,
                'order' => 3
            ],
            [
                'complaint_template_section_id' => 1,
                'enable_field' => 1,
                'field_name' => 'customeraddress',
                'field_type' => 'textarea',
                'default_display_label' => 'Customer Address',
                'field_display_label' => 'Customer Address',
                'is_multiple' => 0,
                'field_is_mandatory' => 0,
                'updated_by' => 0,
                'order' => 4
            ],
            [
                'complaint_template_section_id' => 1,
                'enable_field' => 1,
                'field_name' => 'product',
                'field_type' => 'dropdown',
                'default_display_label' => 'Product / Service ',
                'field_display_label' => 'Product / Service ',
                'is_multiple' => 1,
                'field_is_mandatory' => 1,
                'updated_by' => 0,
                'order' => 5
            ],
            [
                'complaint_template_section_id' => 2,
                'enable_field' => 1,
                'field_name' => 'saleorder',
                'field_type' => 'text',
                'default_display_label' => 'Sale Order Number',
                'field_display_label' => 'Sale Order Number',
                'is_multiple' => 0,
                'field_is_mandatory' => 1,
                'updated_by' => 0,
                'order' => 1
            ],
            [
                'complaint_template_section_id' => 2,
                'enable_field' => 1,
                'field_name' => 'deliveryno',
                'field_type' => 'text',
                'default_display_label' => 'Delivery Number',
                'field_display_label' => 'Delivery Number',
                'is_multiple' => 0,
                'field_is_mandatory' => 1,
                'updated_by' => 0,
                'order' => 2
            ],
            [
                'complaint_template_section_id' => 2,
                'enable_field' => 1,
                'field_name' => 'deliverydate',
                'field_type' => 'date',
                'default_display_label' => 'Delivery Date',
                'field_display_label' => 'Delivery Date',
                'is_multiple' => 0,
                'field_is_mandatory' => 1,
                'updated_by' => 0,
                'order' => 3
            ],
            [
                'complaint_template_section_id' => 3,
                'enable_field' => 1,
                'field_name' => 'itemcode',
                'field_type' => 'text',
                'default_display_label' => 'Item Code',
                'field_display_label' => 'Item Code',
                'is_multiple' => 1,
                'field_is_mandatory' => 1,
                'updated_by' => 0,
                'order' => 1
            ],
            [
                'complaint_template_section_id' => 3,
                'enable_field' => 1,
                'field_name' => 'productdesc',
                'field_type' => 'textarea',
                'default_display_label' => 'Product Description',
                'field_display_label' => 'Product Description',
                'is_multiple' => 1,
                'field_is_mandatory' => 1,
                'updated_by' => 0,
                'order' => 2
            ],
            [
                'complaint_template_section_id' => 3,
                'enable_field' => 1,
                'field_name' => 'sizeofitem',
                'field_type' => 'text',
                'default_display_label' => 'Size of item',
                'field_display_label' => 'Size of item',
                'is_multiple' => 1,
                'field_is_mandatory' => 1,
                'updated_by' => 0,
                'order' => 3
            ],
            [
                'complaint_template_section_id' => 3,
                'enable_field' => 1,
                'field_name' => 'idno',
                'field_type' => 'text',
                'default_display_label' => 'ID No',
                'field_display_label' => 'ID No',
                'is_multiple' => 1,
                'field_is_mandatory' => 1,
                'updated_by' => 0,
                'order' => 4
            ],
            [
                'complaint_template_section_id' => 3,
                'enable_field' => 1,
                'field_name' => 'totalquant',
                'field_type' => 'number',
                'default_display_label' => 'Sale Delivery Quantity',
                'field_display_label' => 'Sale Delivery Quantity',
                'is_multiple' => 1,
                'field_is_mandatory' => 1,
                'updated_by' => 0,
                'order' => 5
            ],
            [
                'complaint_template_section_id' => 3,
                'enable_field' => 1,
                'field_name' => 'affectquant',
                'field_type' => 'number',
                'default_display_label' => 'Affected Quantity',
                'field_display_label' => 'Affected Quantity',
                'is_multiple' => 1,
                'field_is_mandatory' => 1,
                'updated_by' => 0,
                'order' => 6
            ],
            [
                'complaint_template_section_id' => 3,
                'enable_field' => 1,
                'field_name' => 'unit',
                'field_type' => 'text',
                'default_display_label' => 'Unit',
                'field_display_label' => 'Unit',
                'is_multiple' => 1,
                'field_is_mandatory' => 1,
                'updated_by' => 0,
                'order' => 7
            ],
                [
                'complaint_template_section_id' => 3,
                'enable_field' => 1,
                'field_name' => 'costimpact',
                'field_type' => 'text',
                'default_display_label' => 'Cost Impact',
                'field_display_label' => 'Cost Impact',
                'is_multiple' => 1,
                'field_is_mandatory' => 1,
                'updated_by' => 0,
                'order' => 8
            ],
            [
                'complaint_template_section_id' => 3,
                'enable_field' => 1,
                'field_name' => 'complaintcategory',
                'field_type' => 'dropdown',
                'default_display_label' => 'Complaint Category',
                'field_display_label' => 'Complaint Category',
                'is_multiple' => 1,
                'field_is_mandatory' => 1,
                'updated_by' => 0,
                'order' => 9
            ],
            [
                'complaint_template_section_id' => 3,
                'enable_field' => 1,
                'field_name' => 'salereturnreq',
                'field_type' => 'text',
                'default_display_label' => 'Sale Return Request',
                'field_display_label' => 'Sale Return Request',
                'is_multiple' => 1,
                'field_is_mandatory' => 0,
                'updated_by' => 0,
                'order' => 10
            ],
            [
                'complaint_template_section_id' => 3,
                'enable_field' => 1,
                'field_name' => 'reworkreq',
                'field_type' => 'text',
                'default_display_label' => 'Rework Request',
                'field_display_label' => 'Rework Request',
                'is_multiple' => 1,
                'field_is_mandatory' => 0,
                'updated_by' => 0,
                'order' => 11
            ],
            [
                'complaint_template_section_id' => 4,
                'enable_field' => 1,
                'field_name' => 'customer',
                'field_type' => 'dropdown',
                'default_display_label' => 'Customer Name',
                'field_display_label' => 'Customer Name',
                'is_multiple' => 0,
                'field_is_mandatory' => 1,
                'updated_by' => 0,
                'order' => 1
            ],
            [
                'complaint_template_section_id' => 4,
                'enable_field' => 1,
                'field_name' => 'customerphone',
                'field_type' => 'number',
                'default_display_label' => 'Customer Phone',
                'field_display_label' => 'Customer Phone',
                'is_multiple' => 0,
                'field_is_mandatory' => 0,
                'updated_by' => 0,
                'order' => 2
            ],
            [
                'complaint_template_section_id' => 4,
                'enable_field' => 1,
                'field_name' => 'customeremail',
                'field_type' => 'email',
                'default_display_label' => 'Customer Email',
                'field_display_label' => 'Customer Email',
                'is_multiple' => 0,
                'field_is_mandatory' => 1,
                'updated_by' => 0,
                'order' => 3
            ],
            [
                'complaint_template_section_id' => 4,
                'enable_field' => 1,
                'field_name' => 'customeraddress',
                'field_type' => 'textarea',
                'default_display_label' => 'Customer Address',
                'field_display_label' => 'Customer Address',
                'is_multiple' => 0,
                'field_is_mandatory' => 0,
                'updated_by' => 0,
                'order' => 4
            ],
            [
                'complaint_template_section_id' => 4,
                'enable_field' => 1,
                'field_name' => 'product',
                'field_type' => 'dropdown',
                'default_display_label' => 'Product / Service ',
                'field_display_label' => 'Product / Service ',
                'is_multiple' => 1,
                'field_is_mandatory' => 1,
                'updated_by' => 0,
                'order' => 5
            ],
            [
                'complaint_template_section_id' => 5,
                'enable_field' => 1,
                'field_name' => 'job_order_no',
                'field_type' => 'text',
                'default_display_label' => 'Job Order No',
                'field_display_label' => 'Job Order No',
                'is_multiple' => 0,
                'field_is_mandatory' => 1,
                'updated_by' => 0,
                'order' => 1
            ],
            [
                'complaint_template_section_id' => 5,
                'enable_field' => 1,
                'field_name' => 'product_serial_no',
                'field_type' => 'text',
                'default_display_label' => 'Product Serial Number',
                'field_display_label' => 'Product Serial Number',
                'is_multiple' => 0,
                'field_is_mandatory' => 1,
                'updated_by' => 0,
                'order' => 2
            ],
            [
                'complaint_template_section_id' => 6,
                'enable_field' => 1,
                'field_name' => 'project_name',
                'field_type' => 'text',
                'default_display_label' => 'Project Name',
                'field_display_label' => 'Project Name',
                'is_multiple' => 0,
                'field_is_mandatory' => 1,
                'updated_by' => 0,
                'order' => 1
            ],
            [
                'complaint_template_section_id' => 6,
                'enable_field' => 1,
                'field_name' => 'job_history',
                'field_type' => 'textarea',
                'default_display_label' => 'Job History',
                'field_display_label' => 'Job History',
                'is_multiple' => 0,
                'field_is_mandatory' => 1,
                'updated_by' => 0,
                'order' => 2
            ],
            [
                'complaint_template_section_id' => 7,
                'enable_field' => 1,
                'field_name' => 'source_of_issue',
                'field_type' => 'dropdown',
                'default_display_label' => 'Source of Issue',
                'field_display_label' => 'Source of Issue',
                'is_multiple' => 0,
                'field_is_mandatory' => 1,
                'updated_by' => 0,
                'order' => 1
            ],
        ];

        foreach ($datas as $key => $data) {
            if (!DB::table('complaint_template_section_fields')->where('field_name', $data['field_name'])
                ->where('complaint_template_section_id', $data['complaint_template_section_id'])->exists()) {
                DB::table('complaint_template_section_fields')->insert($data);
            }
        }
    }
}
