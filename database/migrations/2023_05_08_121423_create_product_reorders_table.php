<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_reorders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->references('id')->on('companies')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('product_id')->nullable()->references('id')->on('products')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('warehouse_id')->nullable()->references('id')->on('warehouses')->cascadeOnUpdate()->cascadeOnDelete();
            $table->decimal('quantity', 22)->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['product_id', 'warehouse_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_reorders');
    }
};
