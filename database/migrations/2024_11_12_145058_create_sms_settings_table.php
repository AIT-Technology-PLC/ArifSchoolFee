<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_settings', function (Blueprint $table) {
            $table->id();
            $table->string('single_message_url');
            $table->string('bulk_message_url');
            $table->string('security_message_url');
            $table->string('callback')->nullable();
            $table->string('compaign')->nullable();
            $table->string('create_callback')->nullable();
            $table->string('status_callback')->nullable();
            $table->string('message_prefix')->nullable();
            $table->string('message_postfix')->nullable();
            $table->integer('space_before')->nullable();
            $table->integer('space_after')->nullable();
            $table->integer('time_to_live')->nullable();
            $table->integer('code_length')->nullable();
            $table->integer('code_type')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::drop('sms_settings');
    }
};
