<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddElearningToModpageloadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('modpageload', function (Blueprint $table) {
           $table->integer('elearning')->after('enviaspects')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('modpageload', function (Blueprint $table) {
            $table->dropColumn('elearning');
        });
    }
}
