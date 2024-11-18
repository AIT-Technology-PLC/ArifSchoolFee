<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_compensation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('staff_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->date('date_of_joining')->nullable();
            $table->string('job_type')->nullable();
            $table->string('qualifications')->nullable();
            $table->string('experience')->nullable();
            $table->string('efp_number')->nullable();
            $table->decimal('basic_salary', 22)->default(0);
            $table->string('location')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('tin_number')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::drop('staff_compensation');
    }
};
