<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_masters', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('fee_type_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->date('due_date');
            $table->decimal('amount', 22)->default(0);
            $table->string('fine_type')->nullable();
            $table->decimal('fine_amount',22)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });
    }

    public function down(): void
    {
        Schema::drop('fee_masters');
    }
};
