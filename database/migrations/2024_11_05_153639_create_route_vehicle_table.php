<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('route_vehicle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('vehicle_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::drop('route_vehicle');
    }
};
