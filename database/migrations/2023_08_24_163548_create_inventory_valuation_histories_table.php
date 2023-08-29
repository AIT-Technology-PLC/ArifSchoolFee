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
        Schema::create('inventory_valuation_histories', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('unit_cost', 30, 10);
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('inventory_valuation_histories');
    }
};
