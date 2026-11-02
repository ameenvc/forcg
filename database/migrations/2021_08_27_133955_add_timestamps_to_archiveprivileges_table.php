<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimestampsToArchiveprivilegesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('archiveprivileges', 'created_at') && !Schema::hasColumn('archiveprivileges', 'updated_at')) {

            Schema::table('archiveprivileges', function (Blueprint $table) {
                $table->timestamps();
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
        Schema::table('archiveprivileges', function (Blueprint $table) {
            //
        });
    }
}
