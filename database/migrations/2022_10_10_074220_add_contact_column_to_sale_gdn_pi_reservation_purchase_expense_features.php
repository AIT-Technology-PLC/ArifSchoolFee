<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->foreignId('contact_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade')->after('customer_id');
        });

        Schema::table('gdns', function (Blueprint $table) {
            $table->foreignId('contact_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade')->after('customer_id');
        });

        Schema::table('proforma_invoices', function (Blueprint $table) {
            $table->foreignId('contact_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade')->after('customer_id');
        });

        Schema::table('reservations', function (Blueprint $table) {
            $table->foreignId('contact_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade')->after('customer_id');
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->foreignId('contact_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade')->after('supplier_id');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->foreignId('contact_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade')->after('supplier_id');
        });
    }

    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('contact_id');
        });

        Schema::table('gdns', function (Blueprint $table) {
            $table->dropColumn('contact_id');
        });

        Schema::table('proforma_invoices', function (Blueprint $table) {
            $table->dropColumn('contact_id');
        });

        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('contact_id');
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('contact_id');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn('contact_id');
        });
    }
};
