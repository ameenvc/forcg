<?php

use Illuminate\Database\Seeder;

class ComplaintTemplateSeeder extends Seeder
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
                'title' => 'Product Template',
                'description' => 'Standard manufacturing & items',
                'is_active' => 1,
                'order' => 1,
            ],
            [
                'title' => 'Project Template',
                'description' => 'Construction & Service Projects',
                'is_active' => 0,
                'order' => 2,
            ],
        ];

        foreach ($datas as $key => $data) {
            if (!DB::table('complaint_templates')->where('title', $data['title'])->exists()) {
                DB::table('complaint_templates')->insert($data);
            }
        }
    }
}
