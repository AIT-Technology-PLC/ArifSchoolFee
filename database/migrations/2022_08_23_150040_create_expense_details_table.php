<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('expense_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expense_category_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('expense_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('name');
            $table->decimal('quantity', 22);
            $table->decimal('unit_price');
            $table->timestamps();
            $table->softDeletes();

            $table->index('expense_category_id');
            $table->index('expense_id');
        });
    }

    public function down()
    {
        Schema::drop('expense_details');
    }
};