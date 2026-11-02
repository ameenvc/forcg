<?php
use App\Document;
use App\Departments;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDepartmentnameInDocumentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $documents = Document::all();
        foreach($documents as $document)
        {
            $deptid = Departments::where('departmentname',$document->department)->value('id');
            $document->department = $deptid ? $deptid : 0;
            $document->save();

        } 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $documents = Document::all();
        foreach($documents as $document)
        {
            $deptname = Departments::where('id',$document->department)->value('departmentname');
            $document->department = $deptname ? $deptname : '-';
            $document->save();

        }
    }
}
