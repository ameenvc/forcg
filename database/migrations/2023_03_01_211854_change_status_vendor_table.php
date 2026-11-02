<?php

use App\Vendor;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class ChangeStatusVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Schema::disableForeignKeyConstraints();
        $datas = Vendor::where('is_deleted', false)->get();
        foreach ($datas as $data) {
            if ($data->reviewed_at == null && $data->approved_at == null) {
                $status = 'under_review';
            } else if ($data->reviewed_at && $data->approved_at == null) {
                $status = 'awaiting_approval';
            } else if ($data->reviewed_at && $data->approved_at) {
                $status = 'approved';
            }
            Vendor::where('is_deleted', false)->where('id', $data->id)->update([
                'status' => $status
            ]);
        }
        //Schema::enableForeignKeyConstraints();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $datas = Vendor::where('is_deleted', false)->get();
        foreach ($datas as $data) {
            if ($data->reviewed_at == null && $data->approved_at == null) {
                $status = 'Initial Evaluation';
            } else if ($data->reviewed_at && $data->approved_at == null) {
                $status = 'Initial Evaluation';
            } else if ($data->reviewed_at && $data->approved_at) {
                $status = 'Evaluated';
            }
            Vendor::where('is_deleted', false)->where('id', $data->id)->update([
                'status' => $status
            ]);
        }

    }
}
