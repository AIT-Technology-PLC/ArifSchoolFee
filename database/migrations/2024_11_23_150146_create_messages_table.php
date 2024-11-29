<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->string('subject');
            $table->longText('message_content');
            $table->string('type');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });
    }

    public function down(): void
    {
        Schema::drop('messages');
    }
};
