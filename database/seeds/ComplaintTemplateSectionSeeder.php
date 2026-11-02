<?php

use Illuminate\Database\Seeder;

class ComplaintTemplateSectionSeeder extends Seeder
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
                'title' => 'Customer Section',
                'description' => 'Customer Information',
                'complaint_template_id' => 1,
                'show_subfield_question' => 0,
                'order' => 1,
            ],
            [
                'title' => 'Order Details Section',
                'description' => 'Order Information',
                'complaint_template_id' => 1,
                'show_subfield_question' => 0,
                'order' => 1,
            ],
            [
                'title' => 'Product Details Table',
                'description' => 'Product Details',
                'complaint_template_id' => 1,
                'show_subfield_question' => 1,
                'order' => 1,
            ],
            [
                'title' => 'Customer Section',
                'description' => 'Customer Information',
                'complaint_template_id' => 2,
                'show_subfield_question' => 0,
                'order' => 1,
            ],
            [
                'title' => 'Order Details Section',
                'description' => 'Order Information',
                'complaint_template_id' => 2,
                'show_subfield_question' => 0,
                'order' => 1,
            ],
            [
                'title' => 'Project Field Section',
                'description' => 'Product Details',
                'complaint_template_id' => 2,
                'show_subfield_question' => 0,
                'order' => 1,
            ],
            [
                'title' => 'Closure Review Section',
                'description' => 'Closure Review Details',
                'complaint_template_id' => 2,
                'show_subfield_question' => 0,
                'order' => 1,
            ],
        ];

        foreach ($datas as $key => $data) {
            if (!DB::table('complaint_template_sections')->where('title', $data['title'])
                ->where('complaint_template_id', $data['complaint_template_id'])->exists()) {
                DB::table('complaint_template_sections')->insert($data);
            }
        }
    }
}
