<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('arif_pay_transaction_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('gdn_detail_id')->nullable()->constrained('gdn_details')->onDelete('set null');
            $table->string('session_id_number');
            $table->string('notification_url');
            $table->string('uuid');
            $table->string('nonce');
            $table->string('phone');
            $table->decimal('total_amount', 10, 2);
            $table->string('transaction_id');
            $table->string('transaction_status');

            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('gdn_detail_id');
        });
    }

    public function down(): void
    {
        Schema::drop('arif_pay_transaction_details');
    }
};
