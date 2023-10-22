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
        Schema::table('sivs', function (Blueprint $table) {
            $table->foreignId('subtracted_by')->nullable()->after('approved_by')->constrained('users')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sivs', function (Blueprint $table) {
            $table->dropForeign('subtracted_by');
            $table->dropColumn('subtracted_by');
        });
    }
};
