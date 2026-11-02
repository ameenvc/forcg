<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAppDisplayNameInClientDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_details', function (Blueprint $table) {
            $table->string('appname_to_display')->nullable();
        });
        DB::table('client_details')->update([
            'appname_to_display' => config('app.name')
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_details', function (Blueprint $table) {
            $table->dropColumn('appname_to_display');
        });
    }
}
