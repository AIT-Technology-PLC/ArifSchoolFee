<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('school_class_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('section_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('student_category_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('student_group_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('academic_year_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('route_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('vehicle_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('gender');
            $table->date('date_of_birth')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone')->unique()->nullable();
            $table->date('admission_date')->nullable();
            $table->string('father_name')->nullable();
            $table->string('father_phone')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_phone')->nullable();
            $table->string('current_address')->nullable();
            $table->string('permanent_address')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });
    }

    public function down(): void
    {
        Schema::drop('students');
    }
};
