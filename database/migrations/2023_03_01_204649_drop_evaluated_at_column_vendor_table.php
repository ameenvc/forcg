<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropEvaluatedAtColumnVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor', function (Blueprint $table) {
            $table->dropColumn('evaluated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor', function (Blueprint $table) {
            $table->string('evaluated_at')->nullable()->after('per');
        });
    }
}
