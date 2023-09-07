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
        Schema::create('cost_update_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cost_update_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('lifo_unit_cost', 30, 10)->nullable();
            $table->decimal('fifo_unit_cost', 30, 10)->nullable();
            $table->decimal('average_unit_cost', 30, 10)->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('cost_update_id');
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
        Schema::drop('cost_update_details');
    }
};
