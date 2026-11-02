<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimestampsCompanyDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('company_doctors', 'created_at') && !Schema::hasColumn('company_doctors', 'updated_at')) {
            Schema::table('company_doctors', function (Blueprint $table) {
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
        if (Schema::hasColumn('company_doctors', 'created_at') && Schema::hasColumn('company_doctors', 'updated_at')) {
            Schema::table('company_doctors', function (Blueprint $table) {
                $table->dropTimestamps();
            });
        }
    }
}
