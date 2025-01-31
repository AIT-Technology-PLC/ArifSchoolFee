<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
    {
        Schema::create('currency_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('currency_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('exchange_rate', 12, 6);
            $table->string('rate_source');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('currency_histories');
    }
};
