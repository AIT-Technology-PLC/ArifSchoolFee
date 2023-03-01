<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::table('grn_details', function (Blueprint $table) {
            $table->decimal('unit_cost', 30, 10)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('grn_details', function (Blueprint $table) {
            $table->decimal('unit_cost', 22)->default(0.00)->change();
        });
    }
};
