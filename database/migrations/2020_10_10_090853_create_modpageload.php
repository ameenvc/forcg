<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModpageload extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('modpageload')) {
            Schema::create('modpageload', function (Blueprint $table) {
                $table->id();
                $table->integer('correctiveaction')->default(0);
                $table->integer('riskmanagement')->default(0);
                $table->integer('objectives')->default(0);
                $table->integer('opportunity')->default(0);
                $table->integer('compliance')->default(0);
                $table->integer('enviaspects')->default(0);
                $table->integer('auditmanagement')->default(0);
                $table->integer('ohsrisk')->default(0);
                $table->integer('documentmanagement')->default(0);
                $table->integer('complaintmanagement')->default(0);
                $table->integer('contextmanagement')->default(0);
                $table->integer('vendormanagement')->default(0);
                $table->integer('incidentreport')->default(0);
                $table->integer('hse')->default(0);
                $table->integer('hr')->default(0);
                $table->integer('maintainance')->default(0);
                $table->integer('calibration')->default(0);
                $table->integer('occhealth')->default(0);
                $table->integer('wastemanagement')->default(0);
                $table->integer('nearmiss')->default(0);
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
        Schema::dropIfExists('modpageload');
    }
}
