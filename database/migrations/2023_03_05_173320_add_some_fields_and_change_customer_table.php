<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeFieldsAndChangeCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer', function (Blueprint $table) {
            $table->string('location', 255)->change();
            $table->string('customer')->nullable(false)->change();
            $table->string('product', 2000)->nullable(false)->change();
            $table->unsignedBigInteger('country_id');
            $table->unsignedBigInteger('state_id');
            $table->string('pin')->nullable();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('customer_survey_emails', 2000)->nullable();
            $table->dropColumn('contact');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer', function (Blueprint $table) {
            $table->string('location', 2000)->change();
            $table->string('customer')->nullable()->change();
            $table->string('product', 2000)->nullable()->change();
            $table->dropColumn('country_id');
            $table->dropColumn('state_id');
            $table->dropColumn('pin');
            $table->dropColumn('email');
            $table->dropColumn('phone');
            $table->dropColumn('customer_survey_emails');
            $table->string('contact', 2000)->nullable();
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
    }
}
