<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('advancement_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advancement_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('employee_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('gross_salary', 22)->nullable();
            $table->string('job_position');
            $table->timestamps();
            $table->softDeletes();

            $table->index('advancement_id');
        });
    }

    public function down()
    {
        Schema::drop('advancement_details');
    }
};
