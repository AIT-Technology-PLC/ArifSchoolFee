<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('job_extras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_order_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('executed_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->decimal('quantity', 22);
            $table->string('type');
            $table->string('status')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('job_order_id');
            $table->index('product_id');
        });
    }

    public function down()
    {
        Schema::drop('job_extras');
    }
};
