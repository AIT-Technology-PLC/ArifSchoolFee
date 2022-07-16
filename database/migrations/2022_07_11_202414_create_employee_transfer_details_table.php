<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('employee_transfer_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_transfer_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('employee_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes();

            $table->index('employee_transfer_id');
        });
    }

    public function down()
    {
        Schema::drop('employee_transfer_details');
    }
};
