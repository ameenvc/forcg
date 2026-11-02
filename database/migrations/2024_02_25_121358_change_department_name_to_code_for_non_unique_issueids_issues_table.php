<?php

use App\Departments;
use App\Issue;
use Illuminate\Database\Migrations\Migration;

class ChangeDepartmentNameToCodeForNonUniqueIssueidsIssuesTable extends Migration
{
    public function get_duplicates($array)
    {
        return array_unique(array_diff_assoc($array, array_unique($array)));
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $issues = Issue::all();
        foreach ($issues as $issue) {
            $code = Departments::where('id', $issue->department)->value('code');
            $code = strtoupper(substr($code, 0, 3));
            $y = substr($issue->issueid, -2);
            $issue->issueid = $code . $issue->count . '/' . $y;
            $issue->save();
        }
        $issue_ids = Issue::pluck('issueid')->toArray();
        if (!empty($this->get_duplicates($issue_ids))) {
            $issues = Issue::whereIn("issueid", $issue_ids)->get();
            foreach ($issues as $issue) {
                $year = date("Y", strtotime($issue->created_at));
                $code = Departments::where('id', $issue->department)->value('code');
                $code = strtoupper(substr($code, 0, 3));
                $issue->year = $year;
                $issue->issueid = $code . $issue->count . '/' . substr($year, -2);
                $issue->save();
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
