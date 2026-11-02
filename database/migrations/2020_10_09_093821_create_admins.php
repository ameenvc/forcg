<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdmins extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('admins')) {
            Schema::create('admins', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email');
                $table->string('password');
                $table->rememberToken();
                $table->boolean('type')->default(1);
                $table->integer('logdevices')->default(1);
                $table->string('code')->nullable();
                $table->string('employeeid')->default(0);
                $table->string('deptid')->nullable();
                $table->string('appname')->nullable();
                $table->string('appname_to_display')->nullable();
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
        Schema::dropIfExists('admins');
    }
}
