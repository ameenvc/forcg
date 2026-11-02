<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddElearningToModenadisableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('modenadisable', function (Blueprint $table) {
            $table->boolean('elearning')->after('enviaspects')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('modenadisable', function (Blueprint $table) {
            $table->dropColumn('elearning');
        });
    }
}
