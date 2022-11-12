<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inventory_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('model_type');
            $table->bigInteger('model_id');
            $table->boolean('is_subtract');
            $table->decimal('quantity', 22);
            $table->dateTime('issued_on')->nullable();
            $table->dateTime('created')->nullable();
            $table->dateTime('updated')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('product_id');
            $table->index('warehouse_id');
            $table->index('company_id');
        });
    }

    public function down()
    {
        Schema::drop('inventory_histories');
    }
};