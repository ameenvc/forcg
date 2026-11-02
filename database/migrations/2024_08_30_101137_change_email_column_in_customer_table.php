<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeEmailColumnInCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer', function (Blueprint $table) {
            $table->string('email')->nullable(true)->change();
            $table->string('state_id')->nullable(true)->change();
            $table->string('other_country')->nullable()->after('country_id');
            $table->string('other_state')->nullable()->after('state_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
            $table->string('state_id')->nullable(false)->change();
            $table->dropColumn('other_country');
            $table->dropColumn('other_state');
        });
    }
}
