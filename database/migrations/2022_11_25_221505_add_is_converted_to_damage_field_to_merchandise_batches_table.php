<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('merchandise_batches', function (Blueprint $table) {
            $table->foreignId('damage_id')->nullable()->after('merchandise_id')->constrained()->onDelete('set null')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('merchandise_batches', function (Blueprint $table) {
            $table->dropForeign('damage_id');
            $table->dropColumn('damage_id');
        });
    }
};