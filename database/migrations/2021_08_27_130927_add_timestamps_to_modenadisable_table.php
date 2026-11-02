<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimestampsToModenadisableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('modenadisable', 'created_at') && !Schema::hasColumn('modenadisable', 'updated_at')) {

            Schema::table('modenadisable', function (Blueprint $table) {
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
        Schema::table('modenadisable', function (Blueprint $table) {
            //
        });
    }
}
