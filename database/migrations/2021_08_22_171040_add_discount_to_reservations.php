<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscountToReservations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('discount')->nullable()->after('code');
        });

        Schema::table('reservation_details', function (Blueprint $table) {
            $table->string('discount')->nullable()->after('unit_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn(['discount']);
        });

        Schema::table('reservation_details', function (Blueprint $table) {
            $table->dropColumn(['discount']);
        });
    }
}
