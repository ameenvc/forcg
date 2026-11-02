<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeRidOhsriskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ohsrisk', function (Blueprint $table) {
            $table->renameColumn('Rid', 'ohs_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ohsrisk', function (Blueprint $table) {
            $table->renameColumn('ohs_id', 'Rid');
        });
    }
}
