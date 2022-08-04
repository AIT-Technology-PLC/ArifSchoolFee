<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('compensation_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->bigInteger('code');
            $table->dateTime('issued_on')->nullable();
            $table->dateTime('starting_period')->nullable();
            $table->dateTime('ending_period')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['starting_period', 'ending_period']);
            $table->index('company_id');
        });
    }

    public function down()
    {
        Schema::drop('compensation_adjustments');
    }
};