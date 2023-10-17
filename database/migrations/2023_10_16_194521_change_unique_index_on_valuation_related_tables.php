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
        Schema::table('inventory_valuation_balances', function (Blueprint $table) {
            $table->dropUnique('inventory_valuation_balances_model_detail_type');
            $table->unique(['product_id', 'model_detail_type', 'model_detail_id', 'type', 'operation'], 'inventory_valuation_balances_model_detail_type');
        });

        Schema::table('inventory_valuation_histories', function (Blueprint $table) {
            $table->dropUnique('inventory_valuation_histories_model_detail_type');
            $table->unique(['product_id', 'model_detail_type', 'model_detail_id', 'type', 'operation'], 'inventory_valuation_histories_model_detail_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventory_valuation_balances', function (Blueprint $table) {
            $table->dropUnique('inventory_valuation_balances_model_detail_type');
            $table->unique(['model_detail_type', 'model_detail_id', 'type', 'operation'], 'inventory_valuation_balances_model_detail_type');
        });

        Schema::table('inventory_valuation_histories', function (Blueprint $table) {
            $table->dropUnique('inventory_valuation_histories_model_detail_type');
            $table->unique(['model_detail_type', 'model_detail_id', 'type', 'operation'], 'inventory_valuation_histories_model_detail_type');
        });
    }
};
