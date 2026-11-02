<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUpdatedByToManagementAgendaPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('management_agenda_points', function (Blueprint $table) {
            $table->string('updated_by',255)->after('detail_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('management_agenda_points', function (Blueprint $table) {
             $table->dropColumn('updated_by');
        });
    }
}
