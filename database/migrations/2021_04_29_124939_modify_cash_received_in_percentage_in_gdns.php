<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyCashReceivedInPercentageInGdns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gdns', function (Blueprint $table) {
            $table->decimal('cash_received_in_percentage', 22)->change();
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
            $table->decimal('cash_received_in_percentage', 22)->nullable()->change();
        });
    }
}
