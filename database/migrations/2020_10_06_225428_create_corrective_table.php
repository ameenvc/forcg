<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCorrectiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('corrective')) {
            Schema::create('corrective', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('count')->default('0')->nullable('true');
                $table->string('issueid')->nullable('true');
                $table->string('category');
                $table->string('type');
                $table->string('department');
                $table->string('title');
                $table->string('file')->nullable('true');
                $table->string('action_file', 500)->nullable('true');
                $table->string('observation', 1000);
                $table->string('postdate')->nullable('true');
                $table->date('duedate')->nullable('true');
                $table->integer('year')->nullable('true');
                $table->string('agreedaction', 2000)->nullable('true');
                $table->string('rootcause', 2000)->nullable('true');
                $table->string('correctiveaction', 1000)->nullable('true');
                $table->string('correction', 1000)->nullable('true');
                $table->string('status')->default('NULL');
                $table->string('postedby')->nullable('true');
                $table->string('responsibility')->nullable('true');
                $table->string('notification', 2000)->nullable('true');
                $table->string('processstage', 250)->default('Under Review');
                $table->string('approvement')->nullable('true');
                $table->string('impl_status', 500)->default('Not Started-0%')->nullable('true');
                $table->string('remarks', 1000)->nullable('true');
                $table->string('action_remarks', 1000)->nullable('true');
                $table->string('updated_at')->nullable('true');
                $table->date('closed')->nullable('true');
                $table->string('active')->default('yes');
                $table->string('approvedby')->nullable('true');
                $table->string('closedby')->nullable('true');
                $table->string('frequency')->nullable('true');
                $table->date('Monthlyfreq')->nullable('true');
                $table->date('Weeklyfreq')->nullable('true');
                $table->integer('Dayfreq')->default('0');
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
        Schema::dropIfExists('corrective');
    }
}
