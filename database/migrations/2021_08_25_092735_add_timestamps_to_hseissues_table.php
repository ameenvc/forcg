<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimestampsToHseissuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('hseissues', 'created_at') && !Schema::hasColumn('hseissues', 'updated_at')) {

            Schema::table('hseissues', function (Blueprint $table) {
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
        if (Schema::hasColumn('hseissues', 'created_at') && Schema::hasColumn('hseissues', 'updated_at')) {

            Schema::table('hseissues', function (Blueprint $table) {
                $table->dropTimestamps();
            });
        }
    }
}
