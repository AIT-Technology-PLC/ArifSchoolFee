<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('merchandise_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchandise_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('batch_no')->nullable();
            $table->date('expiry_date')->nullable();
            $table->decimal('quantity', 22)->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('merchandise_id');
            $table->index('batch_no');
        });
    }

    public function down()
    {
        Schema::drop('merchandise_batches');
    }
};