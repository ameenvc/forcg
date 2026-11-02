<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('chats')) {
            Schema::create('chats', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('sender_id');
                $table->integer('reciever_id');
                $table->string('message');
                $table->integer('read_status');
                $table->timestamp('time');
                $table->string('date');
                $table->integer('vistosender');
                $table->integer('vistoreciever');
                $table->integer('img');
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
        Schema::dropIfExists('chats');
    }
}
