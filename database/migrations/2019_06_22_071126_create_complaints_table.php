<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComplaintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('complaints')) {
            Schema::create('complaints', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('type');
                $table->string('cname');
                $table->string('location');
                $table->string('product');
                $table->string('productid');
                $table->string('title');
                $table->string('complaint');
                $table->string('file');
                $table->dateTime('postdate');
                $table->date('duedate');
                $table->string('rootcause')->nullable('true');
                $table->string('correctiveaction')->nullable('true');
                $table->string('correction')->nullable('true');
                $table->string('status')->default('open');
                $table->string('responsibility');
                $table->string('approvement')->default('no');
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
        Schema::dropIfExists('complaints');
    }
}
