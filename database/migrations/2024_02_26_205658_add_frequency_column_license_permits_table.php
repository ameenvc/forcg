<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFrequencyColumnLicensePermitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('license_permits', function (Blueprint $table) {
            $table->string('frequency')->after('responsible')->nullable();
            $table->string('Monthlyfreq')->nullable()->after('file');
            $table->string('Weeklyfreq')->nullable()->after('Monthlyfreq');
            $table->integer('Dayfreq')->default(0)->after('Weeklyfreq');
            $table->string('Dailyfreq')->nullable()->after('Dayfreq');
            $table->string('status')->nullable()->after('effectivedate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('license_permits', function (Blueprint $table) {
            $table->dropColumn('frequency');
            $table->dropColumn('Monthlyfreq');
            $table->dropColumn('Weeklyfreq');
            $table->dropColumn('Dayfreq');
            $table->dropColumn('Dailyfreq');
            $table->dropColumn('status');
        });
    }
}
