<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSearchedDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('searched_data', function (Blueprint $table) {
            $table->uuid('id')->index()->primary();
            $table->uuid('user_id');
            $table->uuid('zone_id');
            $table->string('attribute')->nullable();
            $table->uuid('attribute_id')->nullable();
            $table->integer('response_data_count')->default(0);
            $table->integer('volume')->default(0);

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
        Schema::dropIfExists('searched_data');
    }
}
