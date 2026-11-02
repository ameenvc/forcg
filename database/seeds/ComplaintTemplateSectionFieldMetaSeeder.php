<?php

use Illuminate\Database\Seeder;

class ComplaintTemplateSectionFieldMetaSeeder extends Seeder
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
                'complaint_template_section_field_id' => 1,
                'meta_key' => 'lock_enable_field',
                'meta_value' => true
            ], // this row is related customer name of product template
            [
                'complaint_template_section_field_id' => 9,
                'meta_key' => 'lock_enable_field',
                'meta_value' => true
            ], // this row is related item code of product template
            [
                'complaint_template_section_field_id' => 10,
                'meta_key' => 'lock_enable_field',
                'meta_value' => true
            ], // this row is related product desc of product template
            [
                'complaint_template_section_field_id' => 17,
                'meta_key' => 'lock_enable_field',
                'meta_value' => true
            ], // this row is related complaint category of product template
            [
                'complaint_template_section_field_id' => 11,
                'meta_key' => 'lock_enable_field',
                'meta_value' => true
            ], // this row is related size of item in product template
            [
                'complaint_template_section_field_id' => 16,
                'meta_key' => 'lock_enable_field',
                'meta_value' => true
            ], // this row is related cost impact of product template
            [
                'complaint_template_section_field_id' => 9,
                'meta_key' => 'lock_is_mandatory',
                'meta_value' => true
            ], // this row is related item code of product template
            [
                'complaint_template_section_field_id' => 10,
                'meta_key' => 'lock_is_mandatory',
                'meta_value' => true
            ], // this row is related product desc of product template
            [
                'complaint_template_section_field_id' => 17,
                'meta_key' => 'lock_is_mandatory',
                'meta_value' => true
            ], // this row is related complaint category of product template
            [
                'complaint_template_section_field_id' => 20,
                'meta_key' => 'lock_enable_field',
                'meta_value' => true
            ], // this row is related customer name of project template

        ];

        foreach ($datas as $key => $data) {
            if (!DB::table('complaint_template_section_field_metas')->where('meta_key', $data['meta_key'])
                ->where('complaint_template_section_field_id', $data['complaint_template_section_field_id'])->exists()) {
                DB::table('complaint_template_section_field_metas')->insert($data);
            }
        }
    }
}
