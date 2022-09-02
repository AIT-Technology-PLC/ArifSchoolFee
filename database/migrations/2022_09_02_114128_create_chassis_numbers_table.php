<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chassis_numbers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gdn_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('grn_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('job_order_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('merchandise_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('chassis_number')->nullable();
            $table->string('engine_number')->nullable();
            $table->boolean('is_document_received')->default(0);
            $table->boolean('is_added')->default(0);
            $table->boolean('is_subtracted')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['merchandise_id', 'chassis_number']);
            $table->unique(['merchandise_id', 'engine_number']);
            $table->index('merchandise_id');
        });
    }

    public function down()
    {
        Schema::drop('chassis_numbers');
    }
};
