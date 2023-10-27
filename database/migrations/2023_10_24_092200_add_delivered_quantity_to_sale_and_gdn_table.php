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
        Schema::table('sale_details', function (Blueprint $table) {
            $table->decimal('delivered_quantity', 22)->after('quantity')->default(0);
        });

        Schema::table('gdn_details', function (Blueprint $table) {
            $table->decimal('delivered_quantity', 22)->after('quantity')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sale_details', function (Blueprint $table) {
            $table->dropColumn('delivered_quantity');
        });

        Schema::table('gdn_details', function (Blueprint $table) {
            $table->dropColumn('delivered_quantity');
        });
    }
};
