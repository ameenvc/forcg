<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use App\HseIssue;

class UpdateProgressValuesInHseissuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       $issue1 = HseIssue::where('processstage','Action Reviewed, Not Ok')->get(['id', 'processstage','impl_status']);
        foreach($issue1 as $issu)
        {
            $issu->impl_status = 'Progress-10%';
            $issu->save();
        }

        $issue2 = HseIssue::where('processstage','Action Reviewed, Ok')->where('impl_status','Not Started-0%')->get(['id', 'processstage','impl_status']);
        foreach($issue2 as $issu)
        {
            $issu->impl_status = 'Progress-25%';
            $issu->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $issue1 = HseIssue::where('processstage','Action Reviewed, Not Ok')->get(['id', 'processstage','impl_status']);
        foreach($issue1 as $issu)
        {
            $issu->impl_status = 'Not Started-0%';
            $issu->save();
        }
        $issue2 = HseIssue::where('processstage','Action Reviewed, Ok')->where('impl_status','Progress-25%')->get(['id', 'processstage','impl_status']);
        foreach($issue2 as $issu)
        {
            $issu->impl_status = 'Not Started-0%';
            $issu->save();
        } 
    }
}
