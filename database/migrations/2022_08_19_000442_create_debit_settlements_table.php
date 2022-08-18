<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('debit_settlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('debit_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('amount', 22);
            $table->string('method');
            $table->string('bank_name')->nullable();
            $table->string('reference_number')->nullable();
            $table->dateTime('settled_at')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('debit_id');
        });
    }

    public function down()
    {
        Schema::drop('debit_settlements');
    }
};
