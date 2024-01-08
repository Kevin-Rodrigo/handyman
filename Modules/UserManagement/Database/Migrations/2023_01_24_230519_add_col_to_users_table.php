<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('wallet_balance', 24, 3)->default(0);
            $table->decimal('loyalty_point', 24, 3)->default(0);
            $table->string('ref_code', 50)->nullable();
            $table->uuid('referred_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('wallet_balance');
            $table->dropColumn('loyalty_point');
            $table->dropColumn('ref_code');
            $table->dropColumn('referred_by');
        });
    }
}
