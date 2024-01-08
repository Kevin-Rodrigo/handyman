<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColToChannelUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('channel_users', function (Blueprint $table) {
            $table->boolean('is_read')->default(0);
        });

        Schema::table('channel_lists', function (Blueprint $table) {
            $table->dropColumn('is_read');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('channel_users', function (Blueprint $table) {

        });
    }
}
