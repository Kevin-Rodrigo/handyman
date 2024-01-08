<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('discount_type',191)->nullable();
            $table->foreignUuid('zone_id')->nullable();

            $table->decimal('discount_amount',24,3)->default(0);
            $table->string('discount_amount_type',191)->default('percent');

            $table->decimal('min_purchase',24,3)->default(0);
            $table->decimal('max_discount_amount',24,3)->default(0);

            $table->integer('limit_per_user')->default(0);
            $table->string('promotion_type',191)->default('discount');

            $table->boolean('is_active')->default(0);

            $table->date('start_date')->default(now());
            $table->date('end_date')->default(now());

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
        Schema::dropIfExists('discounts');
    }
}
