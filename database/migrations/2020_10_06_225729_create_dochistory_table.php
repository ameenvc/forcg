<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDochistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('dochistory')) {
            Schema::create('dochistory', function (Blueprint $table) {
                $table->integer('sno');
                $table->string('id')->nullable('true');
                $table->integer('count')->default('0');
                $table->longText('request')->nullable('true');
                $table->string('docname')->nullable('true');
                $table->string('erevnumber')->nullable('true');
                $table->string('docno')->nullable('true');
                $table->string('nrevnumber')->nullable('true');
                $table->string('erevdate')->nullable('true');
                $table->string('nrevdate')->nullable('true');
                $table->longText('reason')->nullable('true');
                $table->string('department')->nullable('true');
                $table->string('reqdate',255)->nullable('true');
                $table->timestamp('approvedate')->nullable('true');
                $table->string('ch_approved')->nullable('true');
                $table->string('year')->nullable('true');
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
        Schema::dropIfExists('dochistory');
    }
}
