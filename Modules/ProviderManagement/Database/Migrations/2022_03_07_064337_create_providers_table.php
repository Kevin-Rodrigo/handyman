<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('providers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->nullable();

            $table->string('company_name',191)->nullable();
            $table->string('company_phone',25)->nullable();
            $table->string('company_address',191)->nullable();
            $table->string('company_email',191)->nullable();
            $table->string('logo',191)->nullable();

            $table->string('contact_person_name',191)->nullable();
            $table->string('contact_person_phone',25)->nullable();
            $table->string('contact_person_email',191)->nullable();

            $table->integer('order_count')->unsigned()->default(0);
            $table->integer('service_man_count')->unsigned()->default(0);

            $table->integer('service_capacity_per_day')->unsigned()->default(0);
            $table->integer('rating_count')->unsigned()->default(0);
            $table->float('avg_rating',8,4)->default(0);
            $table->boolean('commission_status')->default(false);
            $table->float('commission_percentage', 8, 4)->default(0);
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
        Schema::dropIfExists('providers');
    }
}
