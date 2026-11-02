<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManagementAgendaPointListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('management_agenda_point_lists', function (Blueprint $table) {
            $table->id();
			$table->text('points');
			$table->string('agenda_type',220);
			$table->string('agenda_point_detail_type',200);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('management_agenda_point_lists');
    }
}
