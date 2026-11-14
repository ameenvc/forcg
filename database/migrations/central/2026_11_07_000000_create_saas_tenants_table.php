<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaasTenantsTable extends Migration
{
    public function up()
    {
        $schema = Schema::connection(config('tenancy.central_connection'));
        $schema->create('saas_tenants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 150);
            $table->string('slug', 63)->unique();
            $table->string('database_name')->unique();
            $table->string('owner_email', 190);
            $table->string('plan')->default('starter');
            $table->string('status')->default('pending');
            $table->timestamp('provisioned_at')->nullable();
            $table->timestamps();
        });
        $schema->create('saas_events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('actor_id')->nullable();
            $table->string('event');
            $table->text('details')->nullable();
            $table->timestamp('created_at');
        });
        if (!$schema->hasTable('super_admins')) {
            $schema->create('super_admins', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
            });
        }
        $schema->create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });
        $schema->create('failed_jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });
    }

    public function down()
    {
        $schema = Schema::connection(config('tenancy.central_connection'));
        foreach (['failed_jobs', 'jobs', 'saas_events', 'saas_tenants'] as $table) {
            $schema->dropIfExists($table);
        }
        // Existing platform administrator accounts are deliberately retained.
    }
}
