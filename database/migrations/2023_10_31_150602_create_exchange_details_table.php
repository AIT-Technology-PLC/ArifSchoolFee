<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exchange_details', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('exchange_detailable', 'exchangedetail');
            $table->foreignId('exchange_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('merchandise_batch_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->decimal('returned_quantity', 22);
            $table->decimal('quantity', 22);
            $table->decimal('unit_price', 22, 2);
            $table->timestamps();
            $table->softDeletes();

            $table->index('exchange_id');
            $table->index('warehouse_id');
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('exchange_details');
    }
};
