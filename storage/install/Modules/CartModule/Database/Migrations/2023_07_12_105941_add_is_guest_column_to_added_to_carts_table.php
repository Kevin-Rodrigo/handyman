<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsGuestColumnToAddedToCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('added_to_carts', function (Blueprint $table) {
            $table->boolean('is_guest')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('added_to_carts', function (Blueprint $table) {
            $table->dropColumn('is_guest');
        });
    }
}
