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
        Schema::table('inventory_histories', function (Blueprint $table) {
            $table->unique(['model_detail_type', 'model_detail_id', 'is_subtract'], 'inventory_histories_model_detail_is_subtract');
        });

        Schema::table('inventory_valuation_balances', function (Blueprint $table) {
            $table->after('unit_cost', function ($table) {
                $table->string('operation')->default('initial');
                $table->nullableMorphs('model_detail', 'inventory_valuation_balances_model_detail_morph_index');
            });

            $table->unique(['model_detail_type', 'model_detail_id', 'type', 'operation'], 'inventory_valuation_balances_model_detail_type');
        });

        Schema::table('inventory_valuation_histories', function (Blueprint $table) {
            $table->after('unit_cost', function ($table) {
                $table->string('operation')->default('initial');
                $table->nullableMorphs('model_detail', 'inventory_valuation_histories_model_detail_morph_index');
            });

            $table->unique(['model_detail_type', 'model_detail_id', 'type', 'operation'], 'inventory_valuation_histories_model_detail_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventory_histories', function (Blueprint $table) {
            $table->dropUnique('inventory_histories_model_detail_is_subtract');
        });

        Schema::table('inventory_valuation_balances', function (Blueprint $table) {
            $table->dropUnique('inventory_valuation_balances_model_detail_type');
            $table->dropColumn('operation');
            $table->dropMorphs('model_detail', 'inventory_valuation_balances_model_detail_morph_index');
        });

        Schema::table('inventory_valuation_histories', function (Blueprint $table) {
            $table->dropUnique('inventory_valuation_histories_model_detail_type');
            $table->dropColumn('operation');
            $table->dropMorphs('model_detail', 'inventory_valuation_histories_model_detail_morph_index');
        });
    }
};
