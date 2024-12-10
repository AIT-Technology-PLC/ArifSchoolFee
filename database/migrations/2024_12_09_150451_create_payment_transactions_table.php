<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assign_fee_master_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('session_id')->unique();
            $table->enum('status', ['pending', 'success', 'failed', 'canceled','unauthorized'])->default('pending');
            $table->string('payment_method');
            $table->json('payment_data')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::drop('payment_transactions');
    }
};
