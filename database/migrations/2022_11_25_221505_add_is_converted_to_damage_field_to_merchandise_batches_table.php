<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('merchandise_batches', function (Blueprint $table) {
            $table->boolean('is_converted_to_damage')->after('batch_no')->default(0);
        });
    }

    public function down()
    {
        Schema::table('merchandise_batches', function (Blueprint $table) {
            $table->dropColumn('is_converted_to_damage');
        });
    }
};