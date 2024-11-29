<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assign_fee_masters', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('student_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('fee_master_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->timestamp('reminder_sent_at')->nullable();
            $table->timestamps();

            $table->index('company_id');
        });
    }

    public function down(): void
    {
        Schema::drop('assign_fee_masters');
    }
};
