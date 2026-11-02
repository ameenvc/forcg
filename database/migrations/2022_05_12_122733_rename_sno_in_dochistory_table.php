<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameSnoInDochistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dochistory', function (Blueprint $table) {
            $table->renameColumn('sno','id');
			$table->dropColumn('id');
        });
		
		Schema::table('dochistory', function (Blueprint $table) {
			$table->id()->first();
		});
		
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dochistory', function (Blueprint $table) {
            $table->renameColumn('id','sno');
        });
    }
}
