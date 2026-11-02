<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCorrectivactionDatatypeInNearmissTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nearmiss', function (Blueprint $table) {
             $table->text('rootcause')->change();
			 $table->text('correctiveaction')->change();
			 $table->text('correction')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nearmiss', function (Blueprint $table) {
            $table->string('rootcause',255)->change();
			$table->string('correctiveaction',255)->change();
			$table->string('correction',255)->change();
        });
    }
}
