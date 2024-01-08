<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingDetailsAmountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_details_amounts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('booking_details_id');
            $table->uuid('booking_id');
            $table->decimal('service_unit_cost',24,2)->default(0);

            $table->decimal('discount_by_admin',24,2)->default(0);
            $table->decimal('discount_by_provider',24,2)->default(0);

            $table->decimal('coupon_discount_by_admin',24,2)->default(0);
            $table->decimal('coupon_discount_by_provider',24,2)->default(0);

            $table->decimal('campaign_discount_by_admin',24,2)->default(0);
            $table->decimal('campaign_discount_by_provider',24,2)->default(0);

            $table->decimal('admin_commission',24,2)->default(0);

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
        Schema::dropIfExists('booking_details_amounts');
    }
}
