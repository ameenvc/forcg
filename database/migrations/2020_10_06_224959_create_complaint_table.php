<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComplaintTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('complaint')) {
            Schema::create('complaint', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('addedby')->nullable('true');
                $table->longText('title')->nullable('true');
                $table->longText('complaint')->nullable('true');
                $table->string('status', 200)->nullable('true');
                $table->string('actionstatus', 200)->nullable('true');
                $table->string('customer')->nullable('true');
                $table->string('product')->nullable('true');
                $table->string('trackno')->nullable('true');
                $table->string('type')->nullable('true');
                $table->longText('action')->nullable('true');
                $table->string('dept')->nullable('true');
                $table->string('cfiles')->nullable('true');
                $table->string('responsibility')->nullable('true');
                $table->longText('rootcause')->nullable('true');
                $table->longText('correctiveaction')->nullable('true');
                $table->longText('correction')->nullable('true');
                $table->string('eremainder')->nullable('true');
                $table->string('sendnotif')->nullable('true');
                $table->string('tardate')->nullable('true');
                $table->longText('reason')->nullable('true');
                $table->timestamp('currenttime')->nullable('true');
                $table->string('reviewed_by', 200)->nullable('true');
                $table->string('reviewedat', 200)->nullable('true');
                $table->string('approved_by', 200)->nullable('true');
                $table->string('approvement', 200)->nullable('true');
                $table->string('active', 20)->nullable('true');
                $table->longText('remarks')->nullable('true');
                $table->string('needaction')->nullable('true');
                $table->string('createdat', 50)->nullable('true');
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
        Schema::dropIfExists('complaint');
    }
}
