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
        Schema::table('gdns', function (Blueprint $table) {
            $table->string('cash_received_type')->default('percent')->after('payment_type');
            $table->renameColumn('cash_received_in_percentage', 'cash_received');
            $table->decimal('cash_received', 22)->default(100)->change();
        });

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
            $table->dropColumn('cash_received');
            $table->renameColumn('cash_received', 'cash_received_in_percentage');
        });
    }
};
