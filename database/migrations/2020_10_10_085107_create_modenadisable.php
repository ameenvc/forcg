<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModenadisable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('modenadisable')) {
            Schema::create('modenadisable', function (Blueprint $table) {
                $table->id();
                $table->boolean('objectives')->default(0);
                $table->boolean('auditmanagement')->default(0);
                $table->boolean('compliance')->default(0);
                $table->boolean('riskmanagement')->default(0);
                $table->boolean('opportunity')->default(0);
                $table->boolean('documentmanagement')->default(0);
                $table->boolean('enviaspects')->default(0);
                $table->boolean('ohsrisk')->default(0);
                $table->boolean('correctiveaction')->default(0);
                $table->boolean('auditschedulelogs')->default(0);
                $table->boolean('complaintmanagement')->default(0);
                $table->boolean('contextmanagement')->default(0);
                $table->boolean('vendormanagement')->default(0);
                $table->boolean('incidentreport')->default(0);
                $table->boolean('hse')->default(0);
                $table->boolean('hr')->default(0);
                $table->boolean('maintainance')->default(0);
                $table->boolean('calibration')->default(0);
                $table->boolean('occhealth')->default(0);
                $table->boolean('wastemanagement')->default(0);
                $table->boolean('nearmiss')->default(0);
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
        Schema::dropIfExists('modenadisable');
    }
}
