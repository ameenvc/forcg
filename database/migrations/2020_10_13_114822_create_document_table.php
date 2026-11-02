<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('document')) {
            Schema::create('document', function (Blueprint $table) {
                $table->bigIncrements('sno');
                $table->integer('id')->nullable('true');
                $table->string('doc_id')->nullable('true');
                $table->string('department')->nullable('true');
                $table->string('doctype', 2000)->nullable('true');
                $table->string('name')->nullable('true');
                $table->string('rid')->nullable('true');
                $table->string('edocname')->default('-')->nullable('true');
                $table->string('edocnumber')->default('-')->nullable('true');
                $table->date('erevdate')->nullable('true');
                $table->string('erevnumber', 2000)->nullable('true');
                $table->string('nrevnumber', 2000)->nullable('true');
                $table->string('ndocname')->default('-')->nullable('true');
                $table->string('ndocnumber')->default('-')->nullable('true');
                $table->date('nrevdate')->nullable('true');
                $table->string('status')->nullable('true');
                $table->timestamp('reviewed_at')->nullable('true');
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
        Schema::dropIfExists('document');
    }
}
