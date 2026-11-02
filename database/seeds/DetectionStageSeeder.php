<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetectionStageSeeder extends Seeder
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
                'name' => 'Received',
            ],
            [
                'name' => 'Processing',
            ],
            [
                'name' => 'Testing',
            ],
            [
                'name' => 'Final Inspection',
            ],
        ];
        foreach ($datas as $data) {
            if (
                !DB::table('corrective_detection_stages')->where('name', $data['name'])
                ->where('deleted_at', null)->exists()
            ) {
                DB::table('corrective_detection_stages')->insert([
                    'name' => $data['name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
