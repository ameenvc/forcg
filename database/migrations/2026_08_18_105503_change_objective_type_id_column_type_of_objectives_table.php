<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeObjectiveTypeIdColumnTypeOfObjectivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('objectives', function (Blueprint $table) {
            $table->json('objective_type_id_new')->nullable();
        });

        DB::statement("
            UPDATE objectives
            SET objective_type_id_new = JSON_ARRAY(objective_type_id)
            WHERE objective_type_id IS NOT NULL
        ");

        Schema::table('objectives', function (Blueprint $table) {
            $table->dropColumn('objective_type_id');
        });

        Schema::table('objectives', function (Blueprint $table) {
            $table->renameColumn('objective_type_id_new', 'objective_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('objectives', function (Blueprint $table) {
            // $table->bigInteger('objective_type_id')->nullable()->change();
        });
    }
}
