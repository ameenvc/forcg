<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeLengthOfFieldsIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('issues', function (Blueprint $table) {
            $table->string('issueid', 20)->change();
            $table->string('category', 20)->change();
            $table->string('type', 20)->change();
            $table->string('department', 50)->change();
            $table->string('title', 191)->change();
            $table->string('file', 1000)->change();
            $table->text('action_file')->change();
            $table->longText('observation')->change();
            $table->longText('agreedaction')->change();
            $table->longText('rootcause')->change();
            $table->longText('correctiveaction')->change();
            $table->longText('correction')->change();
            $table->string('status', 50)->change();
            $table->string('postedby', 191)->change();
            $table->string('responsibility', 191)->change();
            $table->string('processstage', 250)->change();
            $table->string('approvement', 191)->change();
            $table->string('impl_status', 500)->change();
            $table->string('remarks', 1000)->change();
            $table->string('action_remarks', 1000)->change();
            $table->string('active', 20)->change();
            $table->string('approvedby', 250)->change();
            $table->string('closedby', 250)->change();
            $table->string('frequency', 250)->change();
            $table->string('notification', 2000)->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('issues', function (Blueprint $table) {
            $table->string('issueid', 255)->change();
            $table->string('category', 255)->change();
            $table->string('type', 255)->change();
            $table->string('department', 255)->change();
            $table->string('title', 255)->change();
            $table->string('file', 255)->change();
            $table->string('action_file', 255)->change();
            $table->string('observation', 255)->change();
            $table->string('agreedaction', 255)->change();
            $table->string('rootcause', 255)->change();
            $table->string('correctiveaction', 255)->change();
            $table->string('correction', 255)->change();
            $table->string('status', 255)->change();
            $table->string('postedby', 255)->change();
            $table->string('responsibility', 255)->change();
            $table->string('processstage', 255)->change();
            $table->string('approvement', 255)->change();
            $table->string('impl_status', 255)->change();
            $table->string('remarks', 255)->change();
            $table->string('action_remarks', 255)->change();
            $table->string('active', 255)->change();
            $table->string('approvedby', 255)->change();
            $table->string('closedby', 255)->change();
            $table->string('frequency', 255)->change();
            $table->string('notification', 255)->change();


        });
    }
}
