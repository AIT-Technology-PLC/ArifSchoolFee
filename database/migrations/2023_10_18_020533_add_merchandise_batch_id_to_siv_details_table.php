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
        Schema::table('siv_details', function (Blueprint $table) {
            $table->foreignId('merchandise_batch_id')->nullable()->after('product_id')->constrained()->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siv_details', function (Blueprint $table) {
            $table->dropForeign('merchandise_batch_id');
            $table->dropColumn('merchandise_batch_id');
        });
    }
};
