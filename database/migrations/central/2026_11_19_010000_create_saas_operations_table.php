<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaasOperationsTable extends Migration
{
    public function up()
    {
        Schema::connection(config('tenancy.central_connection'))->create('saas_operations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tenant_id')->index();
            $table->unsignedBigInteger('actor_id')->nullable();
            $table->string('hosting_node', 63);
            $table->string('type', 30);
            $table->string('status', 20)->default('queued');
            $table->string('password_hash')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
            $table->index(['hosting_node', 'status']);
        });
    }

    public function down()
    {
        Schema::connection(config('tenancy.central_connection'))->dropIfExists('saas_operations');
    }
}
