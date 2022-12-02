<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('price_increment_details', function (Blueprint $table) {
            $table->text('product_code')->after('product_id');
        });
    }

    public function down()
    {
        Schema::table('price_increment_details', function (Blueprint $table) {
            $table->drop('product_code');
        });
    }
};
