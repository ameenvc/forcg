<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDivisionIdToAuditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audit', function (Blueprint $table) {
            $table->unsignedbigInteger('division_id')->nullable()->after('department');
			$table->unsignedbigInteger('area_id')->nullable()->after('division_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audit', function (Blueprint $table) {
            $table->dropColumn('division_id');
			$table->dropColumn('area_id');
        });
    }
}
