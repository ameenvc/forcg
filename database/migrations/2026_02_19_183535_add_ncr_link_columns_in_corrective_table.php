<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNcrLinkColumnsInCorrectiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('corrective', function (Blueprint $table) {
            $table->enum('is_previously_occurred',['yes','no'])->default('no')->after('observation');
            $table->string('no_of_occurrence')->nullable()->after('is_previously_occurred');
            $table->string('previous_ncr_reference')->nullable()->after('no_of_occurrence');
            $table->string('other_previous_ncr_reference')->nullable()->after('previous_ncr_reference');
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
            $table->dropColumns([
                'is_previously_occurred', 'no_of_occurrence','previous_ncr_reference','other_previous_ncr_reference'
            ]);
        });
    }
}
