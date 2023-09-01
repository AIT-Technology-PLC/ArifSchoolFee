<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('inventory_valuation_method')->default('average')->after('is_active_for_job');
            $table->decimal('lifo_unit_cost', 30, 10)->default(0)->after('inventory_valuation_method');
            $table->decimal('fifo_unit_cost', 30, 10)->default(0)->after('lifo_unit_cost');
            $table->decimal('average_unit_cost', 30, 10)->default(0)->after('fifo_unit_cost');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('inventory_valuation_method');
            $table->dropColumn('lifo_unit_cost');
            $table->dropColumn('fifo_unit_cost');
            $table->dropColumn('average_unit_cost');
        });
    }
};
