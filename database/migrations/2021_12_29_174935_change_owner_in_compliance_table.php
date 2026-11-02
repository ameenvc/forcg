<?php

use App\Compliance;
use App\Departments;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeOwnerInComplianceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $compliances = Compliance::all();
        foreach($compliances as $item)
        {
            $dept = $item->owner;
            $deptid = Departments::where('departmentname',$dept)->value('id');
            $item->owner = $deptid ? $deptid : 0;
            Compliance::where('Cid', $item->Cid)->update([
               'owner' => $item->owner
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $compliances = Compliance::all();
        foreach($compliances as $item)
        {
            $dept = $item->owner;
            $deptname = Departments::where('id',$dept)->value('departmentname');
            $item->owner = $deptname ? $deptname : '-';
            Compliance::where('Cid', $item->Cid)->update([
                'owner' => $item->owner
             ]);

        }
    }
}
