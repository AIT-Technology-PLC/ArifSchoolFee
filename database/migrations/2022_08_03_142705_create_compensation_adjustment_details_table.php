<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('compensation_adjustment_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('adjustment_id')->nullable()->constrained('compensation_adjustments')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('employee_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('compensation_id')->nullable()->constrained('compensations')->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('amount', 22);
            $table->timestamps();
            $table->softDeletes();

            $table->index('adjustment_id');
        });
    }

    public function down()
    {
        Schema::drop('compensation_adjustment_details');
    }
};
