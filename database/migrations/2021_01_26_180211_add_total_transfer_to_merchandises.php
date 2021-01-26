<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalTransferToMerchandises extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchandises', function (Blueprint $table) {
            $table->decimal('total_transfer', 22)->after('total_sold')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchandises', function (Blueprint $table) {
            $table->dropColumn('total_transfer');
        });
    }
}
