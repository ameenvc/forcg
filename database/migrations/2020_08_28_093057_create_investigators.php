<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvestigators extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('investigators')) {
            Schema::create('investigators', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('tleader');
                $table->string('tmembers');
                $table->string('targetdate');
                $table->string('report_no');
                $table->timestamps();
                $table->string('tleadername')->nullable('true');
                $table->string('tmembersnames', 1000)->nullable('true');
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
        Schema::dropIfExists('investigators');
    }
}
