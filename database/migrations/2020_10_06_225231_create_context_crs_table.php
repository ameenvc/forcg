<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContextCrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('context_crs')) {
            Schema::create('context_crs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('count')->default('0');
                $table->string('crid')->nullable('true');
                $table->string('title')->nullable('true');
                $table->string('category')->nullable('true');
                $table->string('scope', 1000)->nullable('true');
                $table->string('reason', 1000)->nullable('true');
                $table->string('benefits', 1000)->nullable('true');
                $table->string('impacts', 1000)->nullable('true');
                $table->string('risk_identified', 1000)->nullable('true');
                $table->string('initiator')->nullable('true');
                $table->string('department')->nullable('true');
                $table->string('changing_product')->nullable('true');
                $table->string('actions', 1000)->nullable('true');
                $table->string('priority')->nullable('true');
                $table->string('rev_authority')->nullable('true');
                $table->string('appr_authority')->nullable('true');
                $table->string('status')->default('Review Pending')->nullable('true');
                $table->string('remarks', 1000)->nullable('true');
                $table->string('proposal', 1000)->nullable('true');
                $table->string('higherremarks', 1000)->nullable('true');
                $table->string('higherproposal', 1000)->nullable('true');
                $table->string('created_at')->nullable('true');
                $table->string('updated_at')->nullable('true');
                $table->string('reviewed_at')->nullable('true');
                $table->string('closed_by')->nullable('true');
                $table->string('reviewed_by')->nullable('true');
                $table->string('approved_by')->nullable('true');
                $table->string('postedby')->nullable('true');
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
        Schema::dropIfExists('context_crs');
    }
}
