<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscountToSales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->decimal('discount', 22)->nullable()->after('code');
        });

        Schema::table('sale_details', function (Blueprint $table) {
            $table->decimal('discount', 22)->nullable()->after('unit_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['discount']);
        });

        Schema::table('sale_details', function (Blueprint $table) {
            $table->dropColumn(['discount']);
        });
    }
}
