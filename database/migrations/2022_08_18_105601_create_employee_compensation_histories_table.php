<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('employee_compensation_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('compensation_id')->nullable()->constrained('compensations')->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('change_count', 22);
            $table->decimal('amount', 22);
            $table->timestamps();
            $table->softDeletes();

            $table->index('employee_id');
        });
    }

    public function down()
    {
        Schema::drop('employee_compensation_histories');
    }
};