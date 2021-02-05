<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyCreatedAndUpdatedByOnDelete extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('merchandises', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('manufacturings', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('raw_materials', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('bill_of_materials', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('mro_items', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('gdns', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('transfers', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('grns', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('merchandises', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('manufacturings', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('raw_materials', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('bill_of_materials', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('mro_items', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('gdns', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('transfers', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('grns', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }
}
