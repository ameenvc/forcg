<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaasCloudflareTables extends Migration
{
    public function up()
    {
        $schema = Schema::connection(config('tenancy.central_connection'));
        $schema->create('saas_cloudflare', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->string('zone_id', 32);
            $table->text('encrypted_token');
            $table->boolean('automatic')->default(false);
            $table->text('targets');
            $table->string('test_status')->nullable();
            $table->string('test_message', 500)->nullable();
            $table->timestamp('tested_at')->nullable();
            $table->timestamps();
        });
        $schema->create('saas_dns_records', function (Blueprint $table) {
            $table->unsignedBigInteger('tenant_id')->primary();
            $table->string('hostname');
            $table->string('hosting_node', 63);
            $table->string('zone_id', 32)->nullable();
            $table->string('record_id', 32)->nullable();
            $table->string('type', 10)->nullable();
            $table->string('content')->nullable();
            $table->boolean('proxied')->default(false);
            $table->string('status', 30);
            $table->string('message', 500);
            $table->timestamp('checked_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        $schema = Schema::connection(config('tenancy.central_connection'));
        $schema->dropIfExists('saas_dns_records');
        $schema->dropIfExists('saas_cloudflare');
    }
}
