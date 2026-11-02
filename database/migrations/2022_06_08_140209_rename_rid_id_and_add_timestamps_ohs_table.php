<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameRidIdAndAddTimestampsOhsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ohs', function (Blueprint $table) {
            $table->renameColumn('Rid', 'id');
            $table->timestamp('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ohs', function (Blueprint $table) {
            $table->renameColumn('id', 'Rid');
            $table->dropColumn('updated_at');
        });
    }
}
