<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColToUserVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_verifications', function (Blueprint $table) {
            $table->timestamp('updated_at')->after('expires_at')->nullable();
            $table->tinyInteger('hit_count')->default('0');
            $table->boolean('is_temp_blocked')->default('0');
            $table->timestamp('temp_block_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_verifications', function (Blueprint $table) {
            $table->dropColumn('updated_at');
            $table->dropColumn('hit_count');
            $table->dropColumn('is_temp_blocked');
            $table->dropColumn('temp_block_time');
        });
    }
}
