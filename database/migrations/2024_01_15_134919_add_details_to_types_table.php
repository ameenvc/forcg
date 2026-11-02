<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetailsToTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('types', function (Blueprint $table) {
            $table->string('text_color')->nullable()->after('name');
            $table->string('background_color')->nullable()->after('text_color');
            $table->text('details')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('types', function (Blueprint $table) {
            $table->dropColumn('text_color');
            $table->dropColumn('background_color');
            $table->dropColumn('details');
        });
    }
}
