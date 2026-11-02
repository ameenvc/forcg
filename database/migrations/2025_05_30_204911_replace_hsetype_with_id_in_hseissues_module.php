<?php

use Illuminate\Database\Migrations\Migration;
use App\HseIssue;
use Illuminate\Support\Facades\DB;
/**
 * Migration to replace hsetype with id in hseissues module.
 * This migration updates the type field in the HseIssue model
 * to use the corresponding id from the hsetypes table.
 */
class ReplaceHsetypeWithIdInHseissuesModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $issue = HseIssue::all();
        $hsetypes = DB::table('hsetypes')->pluck('id', 'hsetype');
        foreach($issue as $issu)
        {
            if (isset($hsetypes[$issu->type])) {
                $issu->type = $hsetypes[$issu->type];
            } else {
                $issu->type = $issu->type; 
            }
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
        $issue = HseIssue::all();
        $hsetypes = DB::table('hsetypes')->pluck('hsetype','id');
        foreach($issue as $issu)
        {
            if (isset($hsetypes[$issu->type])) {
                $issu->type = $hsetypes[$issu->type];
            } else {
                $issu->type = $issu->type; 
            }
            $issu->save();
        }   
    }
}
