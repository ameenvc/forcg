<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDoccontrollersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('doccontrollers')) {
            Schema::create('doccontrollers', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name', 2000)->nullable('true');
                $table->string('email', 2000)->nullable('true');
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
        Schema::dropIfExists('doccontrollers');
    }
}
