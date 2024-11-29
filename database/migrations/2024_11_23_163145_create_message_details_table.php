<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('message_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('employee_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('student_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('staff_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('received_by')->nullable();
            $table->timestamps();

            $table->index('message_id');
        });
    }

    public function down(): void
    {
        Schema::drop('message_details');
    }
};
