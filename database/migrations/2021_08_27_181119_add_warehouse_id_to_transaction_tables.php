<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWarehouseIdToTransactionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adjustments', function (Blueprint $table) {
            $table->bigInteger('warehouse_id')->nullable()->unsigned()->after('id');

            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('damages', function (Blueprint $table) {
            $table->bigInteger('warehouse_id')->nullable()->unsigned()->after('id');

            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('gdns', function (Blueprint $table) {
            $table->bigInteger('warehouse_id')->nullable()->unsigned()->after('id');

            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('grns', function (Blueprint $table) {
            $table->bigInteger('warehouse_id')->nullable()->unsigned()->after('id');

            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('proforma_invoices', function (Blueprint $table) {
            $table->bigInteger('warehouse_id')->nullable()->unsigned()->after('id');

            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->bigInteger('warehouse_id')->nullable()->unsigned()->after('id');

            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->bigInteger('warehouse_id')->nullable()->unsigned()->after('id');

            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('reservations', function (Blueprint $table) {
            $table->bigInteger('warehouse_id')->nullable()->unsigned()->after('id');

            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('returns', function (Blueprint $table) {
            $table->bigInteger('warehouse_id')->nullable()->unsigned()->after('id');

            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->bigInteger('warehouse_id')->nullable()->unsigned()->after('id');

            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('sivs', function (Blueprint $table) {
            $table->bigInteger('warehouse_id')->nullable()->unsigned()->after('id');

            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('tenders', function (Blueprint $table) {
            $table->bigInteger('warehouse_id')->nullable()->unsigned()->after('id');

            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('transfers', function (Blueprint $table) {
            $table->bigInteger('warehouse_id')->nullable()->unsigned()->after('id');

            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('adjustments', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
            $table->dropColumn(['warehouse_id']);
        });

        Schema::table('damages', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
            $table->dropColumn(['warehouse_id']);
        });

        Schema::table('gdns', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
            $table->dropColumn(['warehouse_id']);
        });

        Schema::table('grns', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
            $table->dropColumn(['warehouse_id']);
        });

        Schema::table('proforma_invoices', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
            $table->dropColumn(['warehouse_id']);
        });

        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
            $table->dropColumn(['warehouse_id']);
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
            $table->dropColumn(['warehouse_id']);
        });

        Schema::table('reservations', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
            $table->dropColumn(['warehouse_id']);
        });

        Schema::table('returns', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
            $table->dropColumn(['warehouse_id']);
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
            $table->dropColumn(['warehouse_id']);
        });

        Schema::table('sivs', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
            $table->dropColumn(['warehouse_id']);
        });

        Schema::table('tenders', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
            $table->dropColumn(['warehouse_id']);
        });

        Schema::table('transfers', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
            $table->dropColumn(['warehouse_id']);
        });
    }
}
