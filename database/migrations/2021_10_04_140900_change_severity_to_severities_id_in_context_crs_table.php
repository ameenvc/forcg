<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeSeverityToSeveritiesIdInContextCrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('context_crs', function (Blueprint $table) {
            $table->renameColumn('severity', 'severities_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('context_crs', function (Blueprint $table) {
            $table->renameColumn('severities_id', 'severity');
        });
    }
}
