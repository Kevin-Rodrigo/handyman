<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColToBookingDetailsAmountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('booking_details_amounts', function (Blueprint $table) {
            $table->integer('service_quantity')->default(0)->after('service_unit_cost');
            $table->decimal('service_tax',24,2)->default(0)->after('service_quantity');
            $table->decimal('provider_earning',24,2)->default(0)->after('admin_commission');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booking_details_amounts', function (Blueprint $table) {
            $table->dropColumn('service_quantity');
            $table->dropColumn('service_tax');
            $table->dropColumn('provider_earning');
        });
    }
}
