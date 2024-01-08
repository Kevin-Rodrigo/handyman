<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_details', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('booking_id')->nullable();
            $table->foreignUuid('service_id')->nullable();
            $table->string('variant_key')->nullable();
            $table->decimal('service_cost',24,3)->default(0);
            $table->integer('quantity')->default(1);
            $table->decimal('discount_amount',24,3)->default(0);
            $table->decimal('tax_amount',24,3)->default(0);
            $table->decimal('total_cost',24,3)->default(0);
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
        Schema::dropIfExists('booking_details');
    }
}
