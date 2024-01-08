<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBonusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonuses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('bonus_title');
            $table->text('short_description');
            $table->string('bonus_amount_type');
            $table->decimal('bonus_amount',24,3)->default(0);
            $table->decimal('minimum_add_amount',24,3)->default(0);
            $table->decimal('maximum_bonus_amount',24,3)->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(1);
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
        Schema::dropIfExists('bonuses');
    }
}
