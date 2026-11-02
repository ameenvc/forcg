<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGovernanceHubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('governance_hubs', function (Blueprint $table) {
            $table->id();
            $table->string('document_number')->nullable();
            $table->string('document_name')->nullable();
            $table->string('type')->nullable();
            $table->string('views')->nullable();
            $table->string('revision')->nullable();
            $table->date('date')->nullable();
            $table->string('owner')->nullable();
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
        Schema::dropIfExists('governance_hubs');
    }
}

