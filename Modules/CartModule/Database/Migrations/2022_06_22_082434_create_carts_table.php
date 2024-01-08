<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->foreignUuid('customer_id')->nullable();
            $table->foreignUuid('service_id')->nullable();
            $table->foreignUuid('category_id')->nullable();
            $table->foreignUuid('sub_category_id')->nullable();
            $table->string('variant_key',50)->nullable();
            $table->decimal('service_cost',24,2)->default(0);
            $table->integer('quantity')->default(1);
            $table->decimal('discount_amount',24,2)->default(0);
            $table->decimal('tax_amount',24,2)->default(0);
            $table->decimal('total_cost',24,2)->default(0);
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
        Schema::dropIfExists('carts');
    }
}
