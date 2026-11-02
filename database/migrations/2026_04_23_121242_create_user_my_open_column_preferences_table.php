<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserMyOpenColumnPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('user_my_open_column_preferences')) {
            Schema::create('user_my_open_column_preferences', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('module_name')->nullable();
                $table->string('category_name')->nullable();
                $table->string('column_name')->nullable();
                $table->string('field_name')->nullable();
                $table->boolean('column_filter')->default(false);
                $table->integer('column_position')->nullable();
                $table->boolean('is_dragdropable')->default(true);
                $table->timestamps();
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
        Schema::dropIfExists('user_my_open_column_preferences');
    }
}
