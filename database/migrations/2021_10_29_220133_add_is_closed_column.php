<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsClosedColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->boolean('is_closed')->default(0)->after('code');
        });

        Schema::table('transfers', function (Blueprint $table) {
            $table->boolean('is_closed')->default(0)->after('code');
        });

        Schema::table('proforma_invoices', function (Blueprint $table) {
            $table->boolean('is_closed')->default(0)->after('code');
        });

        Schema::table('gdns', function (Blueprint $table) {
            $table->boolean('is_closed')->default(0)->after('code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn(['is_closed']);
        });

        Schema::table('transfers', function (Blueprint $table) {
            $table->dropColumn(['is_closed']);
        });

        Schema::table('proforma_invoices', function (Blueprint $table) {
            $table->dropColumn(['is_closed']);
        });

        Schema::table('gdns', function (Blueprint $table) {
            $table->dropColumn(['is_closed']);
        });
    }
}
