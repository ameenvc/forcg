<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProcedureAndProcNoToEnviaspectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enviaspects', function (Blueprint $table) {
            $table->string('procedure', 1000)->after('context_id')->nullable();
            $table->string('proc_no', 255)->after('context_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('enviaspects', function (Blueprint $table) {
            $table->dropColumn('procedure');
            $table->dropColumn('proc_no');
        });
    }
}
