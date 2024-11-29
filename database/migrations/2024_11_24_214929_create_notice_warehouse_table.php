<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notice_warehouse', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notice_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::drop('notice_warehouse');
    }
};
