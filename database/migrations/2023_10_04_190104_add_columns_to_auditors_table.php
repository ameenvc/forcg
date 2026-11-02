<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToAuditorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auditors', function (Blueprint $table) {
            $table->text('certifications_licensing')->nullable()->after('certificate');
            $table->string('overall_experience')->nullable()->after('experience');
            $table->text('specialization_area')->nullable()->after('overall_experience');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auditors', function (Blueprint $table) {
            $table->dropColumn(['certifications_licensing', 'specialization_area', 'overall_experience']);
        });
    }
}
