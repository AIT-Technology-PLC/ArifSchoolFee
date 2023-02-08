<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->decimal('exchange_rate', 30, 10)->nullable()->change();

            $table->decimal('other_costs_after_tax', 22)->default(0.00)->after('other_costs');
            $table->renameColumn('other_costs', 'other_costs_before_tax');
        });

        Schema::table('purchase_details', function (Blueprint $table) {
            $table->decimal('unit_price', 30, 10)->change();
        });
    }

    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->decimal('exchange_rate', 22)->nullable()->change();

            $table->dropColumn('other_costs_after_tax');
        });

        Schema::table('purchase_details', function (Blueprint $table) {
            $table->decimal('unit_price', 22)->change();
        });
    }
};
