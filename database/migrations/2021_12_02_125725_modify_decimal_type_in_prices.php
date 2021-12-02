<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyDecimalTypeInPrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prices', function (Blueprint $table) {
            $table->decimal('min_price', 22)->nullable()->change();
            $table->decimal('max_price', 22)->nullable()->change();
            $table->decimal('fixed_price', 22)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prices', function (Blueprint $table) {
            $table->decimal('min_price')->nullable()->change();
            $table->decimal('max_price')->nullable()->change();
            $table->decimal('fixed_price')->nullable()->change();
        });
    }
}
