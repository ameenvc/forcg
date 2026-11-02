<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegulatoryAuthoritiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regulatory_authorities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('status')->nullable();
            $table->integer('no_of_documents')->nullable()->default(0);
            $table->string('initial_name')->nullable();
            $table->string('updated_name')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('modified_by')->nullable();
            $table->dateTime('modified_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
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
        Schema::dropIfExists('regulatory_authority_compliance');
    }
}
