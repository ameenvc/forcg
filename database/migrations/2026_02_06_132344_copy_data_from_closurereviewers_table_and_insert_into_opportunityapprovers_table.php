<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CopyDataFromClosurereviewersTableAndInsertIntoOpportunityapproversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $closurereviewers = DB::table('closurereviewers')
            ->select('name', 'email', 'created_at', 'updated_at')
            ->get()
            ->map(function ($row) {
                return (array) $row;
            })
            ->toArray();
        if(!empty($closurereviewers)){
            DB::table('opportunityapprovers')->insert($closurereviewers);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('opportunityapprovers')->truncate();
    }
}
