<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChangerequestapproves extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('changerequestapproves')) {
            Schema::create('changerequestapproves', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('email');
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
        Schema::dropIfExists('changerequestapproves');
    }
}
