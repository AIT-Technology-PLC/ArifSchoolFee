<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCashReceivedInPercentageToGdns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gdns', function (Blueprint $table) {
            $table->decimal('cash_received_in_percentage', 22)->nullable()->after('payment_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gdns', function (Blueprint $table) {
            $table->dropColumn(['cash_received_in_percentage']);
        });
    }
}
