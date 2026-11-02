<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMocFieldsInCorrectiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('corrective', function (Blueprint $table) {
            if (!Schema::hasColumn('corrective', 'moc_id')) {
            $table->bigInteger('moc_id')->nullable()->after('Dayfreq');
            }
            if (!Schema::hasColumn('corrective', 'change_description')) {
            $table->text('change_description')->nullable()->after('moc_id');
            }
            if (!Schema::hasColumn('corrective', 'is_moc_required')) {
            $table->enum('is_moc_required', ['yes', 'no'])->default('no')->nullable()->after('change_description');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('corrective', function (Blueprint $table) {
            if (Schema::hasColumn('corrective', 'moc_id')) {
                $table->dropColumn('moc_id');
            }
            if (Schema::hasColumn('corrective', 'change_description')) {
                $table->dropColumn('change_description');
            }
            if (Schema::hasColumn('corrective', 'is_moc_required')) {
                $table->dropColumn('is_moc_required');
            }
        });
    }
}
