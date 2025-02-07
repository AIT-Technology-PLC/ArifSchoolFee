<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fee_payments', function (Blueprint $table) {
            $table->string('reference_number')->nullable()->after('payment_mode');
            $table->decimal('exchange_rate', 12, 6)->nullable()->after('reference_number');
        });
    }

    public function down(): void
    {
        Schema::table('fee_payments', function (Blueprint $table) {
            $table->dropColumn('reference_number');
            $table->dropColumn('exchange_rate');
        });
    }
};
