<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWastemastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wastemasters', function (Blueprint $table) {
            $table->id();
			$table->string('department')->nullable();
			$table->string('responsibility')->nullable();
			$table->string('waste_name')->nullable();
			$table->string('waste_type')->nullable();
			$table->string('monthly_expected_qty')->nullable();
			$table->string('yearly_expected_qty')->nullable();
			$table->string('unit')->nullable();
			$table->string('agency')->nullable();
			$table->string('agency_license')->nullable();
			$table->string('posted_by')->nullable();
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
        Schema::dropIfExists('wastemasters');
    }
}
