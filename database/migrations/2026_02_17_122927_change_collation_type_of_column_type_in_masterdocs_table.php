<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCollationTypeOfColumnTypeInMasterdocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('masterdocs', function (Blueprint $table) {
            $table->string('type')->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('masterdocs', function (Blueprint $table) {
            //
        });
    }
}
