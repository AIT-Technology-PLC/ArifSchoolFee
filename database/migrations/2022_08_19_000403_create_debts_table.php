<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('purchase_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->bigInteger('code');
            $table->decimal('cash_amount', 22);
            $table->decimal('debt_amount', 22);
            $table->decimal('debt_amount_settled', 22);
            $table->longText('description')->nullable();
            $table->dateTime('issued_on')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->dateTime('last_settled_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('warehouse_id');
            $table->index('supplier_id');
            $table->unique(['company_id', 'warehouse_id', 'code']);
        });
    }

    public function down()
    {
        Schema::drop('debts');
    }
};
