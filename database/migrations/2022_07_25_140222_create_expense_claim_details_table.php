<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('expense_claim_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expense_claim_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('item');
            $table->decimal('price', 22);
            $table->timestamps();
            $table->softDeletes();

            $table->index('expense_claim_id');
        });
    }

    public function down()
    {
        Schema::drop('expense_claim_details');
    }
};