<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreFrequencyOptionInLicensePermitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('license_permits', function (Blueprint $table) {
            Schema::table('license_permits', function (Blueprint $table) {
                $table->dropColumn('Monthlyfreq');
                $table->dropColumn('Dayfreq');
                $table->string('Weeklyfreq')->default(date('Y-m-d',strtotime('1970-01-01')))->change();
                $table->string('Dailyfreq')->default(date('Y-m-d',strtotime('1970-01-01')))->change();
                $table->string('EveryFivedaysfreq')->default(date('Y-m-d',strtotime('1970-01-01')))->after('Weeklyfreq');
                $table->string('EveryTwodaysfreq')->default(date('Y-m-d',strtotime('1970-01-01')))->after('EveryFivedaysfreq');
            });
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
            $table->string('Monthlyfreq')->nullable()->after('file');
            $table->integer('Dayfreq')->default(0)->after('Weeklyfreq');
            $table->string('Weeklyfreq')->nullable()->change();
            $table->string('Dailyfreq')->nullable()->change();
            $table->dropColumn('EveryFivedaysfreq');
            $table->dropColumn('EveryTwodaysfreq');
        });
    }
}
