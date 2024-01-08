<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostBidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_bids', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->decimal('offered_price',24,2)->default(0);
            $table->text('provider_note')->nullable();
            $table->string('status');

            $table->foreignUuid('post_id');
            $table->foreignUuid('provider_id')->nullable();
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
        Schema::dropIfExists('post_bids');
    }
}
