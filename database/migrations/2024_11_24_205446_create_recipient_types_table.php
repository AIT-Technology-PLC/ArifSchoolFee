<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipient_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notice_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->string('type');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::drop('recipient_types');
    }
};
