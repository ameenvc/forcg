<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExistingControlsColumnToRisksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('risks', function (Blueprint $table) {
            $table->longText('existing_controls')->nullable()->after('riskdri');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('risks', function (Blueprint $table) {
            $table->dropColumn('existing_controls');
        });
    }
}
