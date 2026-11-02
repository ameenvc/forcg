<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('actions')) {
            Schema::create('actions', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('rootcause');
                $table->string('correctiveaction');
                $table->string('correction');
                $table->string('responsibility');
                $table->string('complaintid');
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
        Schema::dropIfExists('actions');
    }
}
