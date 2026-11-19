<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHostingNodeToSaasTenants extends Migration
{
    public function up()
    {
        Schema::connection(config('tenancy.central_connection'))->table('saas_tenants', function (Blueprint $table) {
            $table->string('hosting_node', 63)->nullable()->index();
        });
    }

    public function down()
    {
        Schema::connection(config('tenancy.central_connection'))->table('saas_tenants', function (Blueprint $table) {
            $table->dropIndex(['hosting_node']);
            $table->dropColumn('hosting_node');
        });
    }
}
