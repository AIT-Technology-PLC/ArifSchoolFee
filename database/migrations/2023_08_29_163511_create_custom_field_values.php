<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_field_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_field_id')->references('id')->on('custom_fields')->cascadeOnUpdate()->cascadeOnDelete();
            $table->morphs('custom_field_valuable', 'field_valuable');
            $table->longText('value');
            $table->softDeletes();
            $table->timestamps();

            $table->index('custom_field_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_field_values');
    }
};
