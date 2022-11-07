<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('job_detail_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_detail_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('quantity', 22)->default(0.00);
            $table->string('type');
            $table->timestamps();
            $table->softDeletes();

            $table->index('job_detail_id');
            $table->index('product_id');
        });
    }

    public function down()
    {
        Schema::drop('job_detail_histories');
    }
};