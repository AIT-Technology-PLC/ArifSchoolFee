<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('discount');
        });

        Schema::table('purchase_details', function (Blueprint $table) {
            $table->dropColumn('discount');
        });
    }

    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->decimal('discount', 22)->nullable();
        });

        Schema::table('purchase_details', function (Blueprint $table) {
            $table->decimal('discount', 22)->nullable();
        });
    }
};
