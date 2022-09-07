<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chassis_numbers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gdn_detail_id')->nullable()->constrained()->onDelete('set null')->onUpdate('set null');
            $table->foreignId('grn_detail_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('job_detail_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('chassis_number')->unique();
            $table->string('engine_number')->unique();
            $table->boolean('is_document_received')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('product_id');
            $table->index('warehouse_id');
        });
    }

    public function down()
    {
        Schema::drop('chassis_numbers');
    }
};
