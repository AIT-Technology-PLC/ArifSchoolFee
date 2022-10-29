<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_active')->default(1)->after('batch_priority');
            $table->boolean('is_active_for_sale')->default(1)->after('is_active');
            $table->boolean('is_active_for_purchase')->default(1)->after('is_active_for_sale');
            $table->boolean('is_active_for_job')->default(1)->after('is_active_for_purchase');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_active');
            $table->dropColumn('is_active_for_sale');
            $table->dropColumn('is_active_for_purchase');
            $table->dropColumn('is_active_for_job');
        });
    }
};