<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TnxAdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->foreignUuid('ref_trx_id')->nullable();
            $table->foreignUuid('booking_id')->nullable();
            $table->string('trx_type')->nullable();
            $table->decimal('debit',24,2)->default(0);
            $table->decimal('credit',24,2)->default(0);
            $table->decimal('balance',24,2)->default(0);
            $table->foreignUuid('from_user_id')->nullable();
            $table->foreignUuid('to_user_id')->nullable();
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
