<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('currencies', function (Blueprint $table) {
            $table->decimal('exchange_rate', 12, 6)->nullable()->after('symbol');
            $table->string('rate_source')->nullable()->after('exchange_rate');
            $table->boolean('enabled')->default('0')->after('rate_source');

        });
    }

    public function down(): void
    {
        Schema::table('currencies', function (Blueprint $table) {
            $table->dropColumn('exchange_rate');
            $table->dropColumn('rate_source');
            $table->dropColumn('enabled');
        });
    }
};
