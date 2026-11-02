<?php

use App\Audit;
use App\Departments;
use Illuminate\Database\Migrations\Migration;

class UpdateNonUniqueIssueidsAuditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $audit_issues = Audit::all();
        foreach($audit_issues as $a) {
            $code = Departments::where('id', $a->department)->value('code');
            $code = strtoupper(substr($code, 0, 3));
            $year = substr($a->year, -2);
            $a->aud_id = $code . $a->count . '/' . $year;
            $a->save();
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
