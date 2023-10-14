<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('inventory_histories', function (Blueprint $table) {
            $table->dropUnique('inventory_histories_model_detail_is_subtract');

            $table->unique(['model_detail_type', 'model_detail_id', 'product_id', 'is_subtract'], 'inventory_histories_model_detail_is_subtract');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
