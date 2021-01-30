<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToPurchase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->string('purchase_no')->nullable()->after('updated_by');
            $table->string('payment_type')->default('Cash Payment')->after('purchase_no');
            $table->boolean('is_manual')->default(0)->after('payment_type');
        });

        Schema::table('purchase_details', function (Blueprint $table) {
            $table->bigInteger('warehouse_id')->nullable()->unsigned()->after('product_id');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('shipping_line');
            $table->dropColumn('shipped_at');
            $table->dropColumn('delivered_at');
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
            $table->dropColumn('purchase_no');
            $table->dropColumn('payment_type');
            $table->dropColumn('is_manual');
        });

        Schema::table('purchase_details', function (Blueprint $table) {
            $table->dropColumn('warehouse_id');
        });
    }
}
