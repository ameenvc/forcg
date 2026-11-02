<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ToImplementTwoFactorAuthenticationAddNewColumnsToAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->boolean('enable_2fa')->default(0);
            $table->text('google2fa_secret')->nullable();
            $table->text('google2fa_setup_secret')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('enable_2fa');
            $table->dropColumn('google2fa_secret');
            $table->dropColumn('google2fa_setup_secret');
        });
    }
}
