<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('issues')) {
            Schema::create('issues', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('count')->default('0');
                $table->string('issueid')->nullable('true');
                $table->string('action_file')->nullable('true');
                $table->integer('year')->nullable('true');
                $table->string('notification')->nullable('true');
                $table->string('category');
                $table->string('type');
                $table->string('department');
                $table->string('title');
                $table->string('file')->nullable('true');
                $table->string('observation')->nullable('true');
                //$table->string('postdate')->nullable('true');
                $table->date('duedate')->nullable('true');
                $table->string('agreedaction')->nullable('true');
                $table->string('rootcause')->nullable('true');
                $table->string('correctiveaction')->nullable('true');
                $table->string('correction')->nullable('true');
                $table->string('status')->default('NULL');
                $table->string('postedby')->nullable('true');
                $table->string('responsibility')->nullable('true');
                $table->string('processstage')->default('Under Review');
                $table->string('approvement')->nullable('true');
                $table->string('impl_status')->default('Not Started-0%')->nullable('true');
                $table->string('remarks')->nullable('true');
                $table->string('action_remarks')->nullable('true');
                //$table->string('updated_at')->nullable('true');
                $table->date('closed')->nullable('true');
                $table->string('active')->default('yes');
                $table->string('approvedby')->nullable('true');
                $table->string('closedby')->nullable('true');
                $table->string('frequency')->nullable('true');
                $table->date('Monthlyfreq')->nullable('true');
                $table->date('Weeklyfreq')->nullable('true');
                $table->integer('Dayfreq')->default('0');
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
        Schema::dropIfExists('issues');
    }
}
