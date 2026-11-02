<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanysize extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('companysize')) {
            Schema::create('companysize', function (Blueprint $table) {
                $table->id();
                $table->integer('compsize')->default(0);
                $table->string('name');
                $table->string('email', 300);
                $table->integer('loginsperperson')->default(1);
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
        Schema::dropIfExists('companysize');
    }
}
