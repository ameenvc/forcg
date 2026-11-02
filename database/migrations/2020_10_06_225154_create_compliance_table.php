<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComplianceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('compliance')) {
            Schema::create('compliance', function (Blueprint $table) {
                $table->bigIncrements('Cid');
                $table->tinyInteger('Hidden')->default('0');
                $table->integer('count')->default('0');
                $table->string('auth_id')->nullable('true');
                $table->string('comp_status')->default('Under Review');
                $table->string('pro_status')->default('Waiting for HOD Review');
                $table->string('frequency')->nullable('true');
                $table->string('reason')->nullable('true');
                $table->string('remarks')->nullable('true');
                $table->string('issue', 2000)->nullable('true');
                $table->string('version')->nullable('true');
                $table->string('regulation')->nullable('true');
                $table->string('authority')->nullable('true');
                $table->string('requirement', 2000)->nullable('true');
                $table->string('aspect', 2000)->nullable('true');
                $table->string('owner')->nullable('true');
                $table->date('duedate')->nullable('true');
                $table->text('incompliance')->nullable('true');
                $table->string('department')->nullable('true');
                $table->string('postedby')->nullable('true');
                $table->string('person_control')->nullable('true');
                $table->string('person_monitor')->nullable('true');
                $table->string('visibleto', 2000)->nullable('true');
                $table->string('created_at')->nullable('true');
                $table->string('freq_review')->nullable('true');
                $table->string('updatedat')->nullable('true');
                $table->string('approvedby')->nullable('true');
                $table->string('closedby')->nullable('true');
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
        Schema::dropIfExists('compliance');
    }
}
