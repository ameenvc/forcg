<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeEmailToBeUniqueInArchiveprivilegesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('archiveprivileges', function (Blueprint $table) {
            $table->unique('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('archiveprivileges', function (Blueprint $table) {
            $table->dropUnique(['email']);
        });
    }
}
