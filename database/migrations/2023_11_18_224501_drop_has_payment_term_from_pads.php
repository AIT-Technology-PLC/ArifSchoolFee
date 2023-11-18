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
        Schema::table('pads', function (Blueprint $table) {
            $table->dropColumn('has_payment_term');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pads', function (Blueprint $table) {
            $table->boolean('has_payment_term')->default(0)->after('has_prices');
        });
    }
};
