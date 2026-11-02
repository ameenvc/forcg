<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_histories', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('customer_id')->nullable();

            $table->string('filled_by_name');
            $table->string('filled_by_position')->nullable();


            $table->timestamp('evaluation_date')->nullable();
            $table->double('overall_score');
            $table->enum('status', ['poor', 'below_average', 'fair', 'good', 'excellent']);
            $table->string('type')->nullable();
            $table->json('answers');
            $table->boolean('is_deleted')->default(0);
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
        Schema::dropIfExists('customer_histories');
    }
}
