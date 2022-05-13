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
        Schema::table('gdns', function (Blueprint $table) {
            $table->string('cash_received_type')->default('percent')->after('payment_type');
            $table->renameColumn('cash_received_in_percentage', 'cash_received');
        });
        DB::table('gdns')->update(['cash_received_type' => 'percent']);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gdns', function (Blueprint $table) {
            $table->dropColumn('cash_received_type');
            $table->renameColumn('cash_received', 'cash_received_in_percentage');
        });
    }
};
