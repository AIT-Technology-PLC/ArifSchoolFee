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
        Schema::create('custom_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->references('id')->on('companies')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();
            $table->string('label');
            $table->string('tag');
            $table->string('tag_type')->nullable();
            $table->string('placeholder')->nullable();
            $table->longText('options')->nullable();
            $table->string('default_value')->nullable();
            $table->string('model_type');
            $table->integer('order');
            $table->string('column_size')->default(6);
            $table->string('icon');
            $table->boolean('is_active');
            $table->boolean('is_required');
            $table->boolean('is_unique');
            $table->boolean('is_visible');
            $table->boolean('is_printable');
            $table->boolean('is_master');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_fields');
    }
};
