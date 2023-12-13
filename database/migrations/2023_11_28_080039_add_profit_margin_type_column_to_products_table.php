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
        Schema::table('products', function (Blueprint $table) {
            $table->string('profit_margin_type')->default('percent')->after('average_unit_cost');
            $table->decimal('profit_margin_amount', 22, 2)->default(0.00)->after('profit_margin_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('profit_margin_type');
            $table->dropColumn('profit_margin_amount');
        });
    }
};
