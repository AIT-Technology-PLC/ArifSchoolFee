<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveIsManualFromPurchaseAndSale extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn(['is_manual', 'status']);
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['is_manual', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->boolean('is_manual');
            $table->string('status')->nullable();
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->boolean('is_manual');
            $table->string('status')->nullable();
        });
    }
}
