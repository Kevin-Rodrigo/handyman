<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('customer_id')->nullable();
            $table->foreignUuid('provider_id')->nullable();
            $table->foreignUuid('zone_id')->nullable();
            $table->string('booking_status')->default('pending');
            $table->boolean('is_paid')->default(0);
            $table->string('payment_method')->default('cash');
            $table->string('transaction_id')->nullable();
            $table->decimal('total_order_amount',24,3)->default(0);
            $table->decimal('total_tax_amount',24,3)->default(0);
            $table->decimal('total_discount_amount',24,3)->default(0);
            $table->dateTime('service_schedule')->nullable();
            $table->foreignUuid('service_address_id')->nullable();
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
        Schema::dropIfExists('bookings');
    }
}
