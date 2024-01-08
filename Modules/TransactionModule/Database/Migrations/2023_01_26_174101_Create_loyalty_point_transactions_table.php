<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoyaltyPointTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loyalty_point_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->uuid('user_id');
            $table->decimal('credit',24,2)->default(0);
            $table->decimal('debit',24,2)->default(0);
            $table->decimal('balance',24,2)->default(0);
            $table->string('reference')->nullable();
            $table->string('transaction_type')->nullable();
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
        Schema::dropIfExists('loyalty_point_transactions');
    }
}
