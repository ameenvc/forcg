<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessNcrFieldHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('process_ncr_field_histories')){
            Schema::create('process_ncr_field_histories', function (Blueprint $table) {
                $table->id();
                $table->string('field_name')->nullable();
                $table->string('previous_name')->nullable();
                $table->string('updated_name')->nullable();
                $table->string('initial_name')->nullable();
                $table->string('modifier')->nullable();
                $table->dateTime('modified_at')->nullable();
                $table->softDeletes();
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
        Schema::dropIfExists('process_ncr_field_histories');
    }
}
