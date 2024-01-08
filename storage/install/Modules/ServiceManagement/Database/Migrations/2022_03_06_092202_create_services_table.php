<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name',191)->nullable();
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->string('cover_image',191)->nullable();
            $table->string('thumbnail',191)->nullable();
            $table->foreignUuid('category_id')->nullable();
            $table->foreignUuid('sub_category_id')->nullable();
            $table->decimal('tax',24,3)->default(0);
            $table->integer('order_count')->unsigned()->default(0);
            $table->boolean('is_active')->default(1);
            $table->integer('rating_count')->unsigned()->default(0);
            $table->float('avg_rating',8,4)->default(0);
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
        Schema::dropIfExists('services');
    }
}
