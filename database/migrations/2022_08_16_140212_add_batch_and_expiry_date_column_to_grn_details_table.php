<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('grn_details', function (Blueprint $table) {
            $table->string('batch_no')->nullable()->after('quantity');
            $table->date('expiry_date')->nullable()->after('batch_no');
        });
    }

    public function down()
    {
        Schema::table('grn_details', function (Blueprint $table) {
            $table->dropColumn('batch_no');
            $table->dropColumn('expiry_date');
        });
    }
};