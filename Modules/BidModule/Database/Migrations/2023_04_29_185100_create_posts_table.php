<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('service_description')->nullable();
            $table->dateTime('booking_schedule')->nullable();
            $table->boolean('is_booked')->default(0);

            $table->foreignUuid('customer_user_id');
            $table->foreignUuid('service_id')->nullable();
            $table->foreignUuid('category_id')->nullable();
            $table->foreignUuid('sub_category_id')->nullable();
            $table->foreignUuid('service_address_id')->nullable();
            $table->foreignUuid('booking_id')->nullable();

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
        Schema::dropIfExists('posts');
    }
}
