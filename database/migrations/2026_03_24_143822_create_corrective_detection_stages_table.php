<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCorrectiveDetectionStagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('corrective_detection_stages')){
        
            Schema::create('corrective_detection_stages', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('created_by')->nullable();
                $table->string('modified_by')->nullable();
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
        Schema::dropIfExists('corrective_detection_stages');
    }
}
