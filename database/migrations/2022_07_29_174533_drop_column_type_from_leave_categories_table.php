<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('leave_categories', function (Blueprint $table) {
            $table->dropUnique(['company_id', 'name', 'type']);
            $table->dropColumn('type');
            $table->unique(['company_id', 'name']);
        });
    }

    public function down()
    {
        Schema::table('leave_categories', function (Blueprint $table) {
            //
        });
    }
};