<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimestampsToDoccontrollersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('doccontrollers', 'created_at') && !Schema::hasColumn('doccontrollers', 'updated_at')) {
            Schema::table('doccontrollers', function (Blueprint $table) {
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('doccontrollers', function (Blueprint $table) {
            //
        });
    }
}
