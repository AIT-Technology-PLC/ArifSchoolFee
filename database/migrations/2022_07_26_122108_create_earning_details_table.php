<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('earning_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('earning_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('earning_category_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('employee_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('amount', 22);
            $table->timestamps();
            $table->softDeletes();

            $table->index('earning_id');
            $table->index('earning_category_id');
        });
    }

    public function down()
    {
        Schema::drop('earning_details');
    }
};
