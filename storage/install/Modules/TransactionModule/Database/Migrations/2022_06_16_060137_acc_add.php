<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AccAdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->foreignUuid('user_id')->nullable();
            $table->decimal('balance_pending',24,2)->default(0);
            $table->decimal('received_balance',24,2)->default(0);
            $table->decimal('account_payable',24,2)->default(0);
            $table->decimal('account_receivable',24,2)->default(0);
            $table->decimal('total_withdrawn',24,2)->default(0);
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
        //
    }
}
