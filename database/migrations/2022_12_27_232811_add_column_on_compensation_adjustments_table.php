<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('compensation_adjustment_details', function (Blueprint $table) {
            $table->longText('description')->nullable()->after('amount');
        });
    }

    public function down()
    {
        Schema::table('compensation_adjustment_details', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
};
