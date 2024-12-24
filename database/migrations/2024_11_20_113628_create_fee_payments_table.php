<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('student_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('student_history_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('fee_discount_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('assign_fee_master_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->string('payment_mode');
            $table->date('payment_date');
            $table->decimal('amount');
            $table->decimal('fine_amount');
            $table->decimal('discount_amount');
            $table->decimal('commission_amount');
            $table->date('discount_month')->nullable();
            $table->timestamps();

            $table->index('company_id');
            $table->index('student_id');
            $table->index('student_history_id');
        });
    }

    public function down(): void
    {
        Schema::drop('fee_payments');
    }
};
