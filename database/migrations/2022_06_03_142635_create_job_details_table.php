<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('job_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('bill_of_material_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('quantity', 22);
            $table->decimal('wip', 22)->default(0.00);
            $table->decimal('available', 22)->default(0.00);
            $table->timestamps();
            $table->softDeletes();

            $table->index('job_id');
            $table->index('product_id');
            $table->index('bill_of_material_id');
        });
    }

    public function down()
    {
        Schema::drop('job_details');
    }
};