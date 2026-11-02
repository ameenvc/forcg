<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActionStatusToContextCrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('context_crs', function (Blueprint $table) {
            $table->enum('action_status', ['Completed-100%', 'Partially Completed-50%', 'Progress-25%', 'Not Started-0%'])->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('context_crs', function (Blueprint $table) {
            //
        });
    }
}
