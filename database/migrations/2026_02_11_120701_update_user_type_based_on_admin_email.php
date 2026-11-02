<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUserTypeBasedOnAdminEmail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // set 1 where email exists in admins
        DB::table('users')
            ->whereIn('email', function ($query) {
                $query->select('email')->from('admins');
            })
            ->update(['type' => 1]);

        // set 0 where email NOT exists in admins
        DB::table('users')
            ->whereNotIn('email', function ($query) {
                $query->select('email')->from('admins');
            })
            ->update(['type' => 0]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
