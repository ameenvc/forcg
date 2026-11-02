<?php

use Illuminate\Database\Migrations\Migration;
use App\Vendor;

class ChangeCreatedAtFormatVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $vendors = Vendor::all();
        foreach ($vendors as $v) {
            if ($v->created_at != null) {
                Vendor::where('id', $v->id)->update([
                    'created_at' => date('Y-m-d H:i:s', strtotime($v->created_at)),
                ]);
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

    }
}
