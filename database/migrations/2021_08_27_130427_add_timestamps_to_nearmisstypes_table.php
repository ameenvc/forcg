<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimestampsToNearmisstypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('nearmisstypes', 'created_at') && !Schema::hasColumn('nearmisstypes', 'updated_at')) {
            Schema::table('nearmisstypes', function (Blueprint $table) {
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
        Schema::table('nearmisstypes', function (Blueprint $table) {
            //
        });
    }
}
