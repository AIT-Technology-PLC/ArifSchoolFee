<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_class_section', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_class_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('section_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::drop('school_class_section');
    }
};
