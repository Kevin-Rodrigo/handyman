<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('users')){
            Schema::create('users', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('first_name',191)->nullable();
                $table->string('last_name',191)->nullable();
                $table->string('email',191)->nullable();
                $table->string('phone',191)->nullable();
                $table->string('identification_number',191)->nullable();
                $table->string('identification_type',191)->default('nid');
                $table->string('identification_image', 191)->default('def.png');
                $table->date('date_of_birth')->nullable();
                $table->string('gender',191)->default('male');
                $table->string('profile_image',191)->default('default.png');
                $table->string('fcm_token',191)->nullable();
                $table->boolean('is_phone_verified')->default(0);
                $table->boolean('is_email_verified')->default(0);
                $table->timestamp('phone_verified_at')->nullable();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password',191)->nullable();
                $table->boolean('is_active')->default(0);
                $table->foreignUuid('provider_id')->nullable();
                $table->string('user_type',191)->default('customer'); // super-admin, admin-employee, customer, provider-admin, provider-employee
                $table->rememberToken();
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
