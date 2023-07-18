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
        Schema::table('companies', function (Blueprint $table) {
            $table->string('sales_subtracting_method')->default('none');
        });

        Schema::table('sale_details', function (Blueprint $table) {
            $table->foreignId('warehouse_id')->after('product_id')->nullable()->references('id')->on('warehouses')->cascadeOnDelete()->cascadeOnUpdate();
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->foreignId('subtracted_by')->nullable()->after('approved_by')->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('added_by')->nullable()->after('subtracted_by')->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('sales_subtracting_method');
        });

        Schema::table('sale_details', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
            $table->dropColumn('warehouse_id');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['subtracted_by']);
            $table->dropForeign(['added_by']);

            $table->dropColumn('subtracted_by');
            $table->dropColumn('added_by');
        });
    }
};
