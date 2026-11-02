<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLicensepermitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('license_permits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('comp_id');
            $table->string('name');
            $table->string('expirydate');
            $table->string('responsible');
            $table->longText('renewal_requirements')->nullable();
            $table->longText('associated_regulations')->nullable();
            $table->string('file');
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
        Schema::dropIfExists('licensepermits');
    }
}
