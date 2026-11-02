<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddResponsibilityToWasteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		if(!Schema::hasColumn('waste', 'responsibility')) 
		{
			Schema::table('waste', function (Blueprint $table) {
				$table->string('responsibility',300)->nullable()->after('department');
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
        Schema::table('waste', function (Blueprint $table) {
            $table->dropColumn('responsibility');
        });
    }
}
