<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_batchable')->default(0)->after('min_on_hand');
            $table->string('batch_priority')->nullable()->after('is_batchable');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_batchable');
            $table->dropColumn('batch_priority');
        });
    }
};
