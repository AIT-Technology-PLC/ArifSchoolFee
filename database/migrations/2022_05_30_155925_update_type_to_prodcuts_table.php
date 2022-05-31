<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prodcuts', function (Blueprint $table) {
            //
        });
        DB::table('products')->update(['type' => 'Finished Goods']);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prodcuts', function (Blueprint $table) {
            //
        });
        DB::table('products')->update(['type' => 'Merchandise Inventory']);
    }
};
