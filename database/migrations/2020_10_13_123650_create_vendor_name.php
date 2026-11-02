<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('vendor')) {
            Schema::create('vendor', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('vendor_name');
                $table->string('vendor_group');
                $table->string('scope');
                $table->string('address');
                $table->string('location');
                $table->string('contact');
                $table->string('Basis_addition')->nullable();
                $table->string('Iso_standard');
                $table->year('Yoe');
                $table->string('comments')->nullable();
                $table->string('trial_order')->nullable();
                $table->date('approved_date')->nullable();
                $table->string('basis_approval')->nullable();
                $table->string('vendor_type');
                $table->string('outcome')->nullable();
                $table->string('eval');
                $table->string('status');
                $table->string('created_at');
                $table->string('added_by', 500);
                $table->string('recommend')->default('null');
                $table->decimal('score')->nullable();
                $table->string('total')->nullable();
                $table->decimal('per')->nullable();
                $table->string('evaluated_at')->nullable();
                $table->string('evaluated_by')->nullable();
                $table->date('due_date')->nullable();
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
        Schema::dropIfExists('vendor');
    }
}
