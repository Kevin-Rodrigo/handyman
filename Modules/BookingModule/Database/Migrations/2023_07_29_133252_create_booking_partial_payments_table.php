<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingPartialPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_partial_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('booking_id');
            $table->string('paid_with');
            $table->decimal('paid_amount',24,3)->default(0);
            $table->decimal('due_amount',24,3)->default(0);

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
        Schema::dropIfExists('booking_partial_payments');
    }
}
