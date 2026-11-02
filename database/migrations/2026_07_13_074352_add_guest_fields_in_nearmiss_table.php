<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGuestFieldsInNearmissTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nearmiss', function (Blueprint $table) {
            $table->string('reporter_type')->nullable();
            $table->string('employeeid')->nullable();
            $table->string('reporter_name')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('email')->nullable();
            $table->string('company_name')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('type')->nullable()->change();
            $table->string('department_reported_by')->nullable()->change();
            $table->string('position')->nullable()->change();
            $table->string('initial_reviewed_by')->nullable();
            $table->timestamp('initial_reviewed_at')->nullable();
            $table->longText('initial_reviewed_rejection')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nearmiss', function (Blueprint $table) {
            $table->dropColumns([
                'reporter_type',
                'employeeid',
                'reporter_name','initial_reviewed_rejection',
                'mobile_number',
                'email','initial_reviewed_by','initial_reviewed_at',
                'company_name',
                'ip_address',
            ]);
            $table->string('type')->change();
            $table->string('department_reported_by')->change();
            $table->string('position')->change();
        });
    }
}
