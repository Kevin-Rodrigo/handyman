<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColToBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('additional_tax_amount',24,2)->default(0);
            $table->decimal('additional_discount_amount',24,2)->default(0);
            $table->decimal('additional_campaign_discount_amount',24,2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('additional_tax_amount');
            $table->dropColumn('additional_discount_amount');
            $table->dropColumn('additional_campaign_discount_amount');
        });
    }
}
