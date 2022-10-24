<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->foreignId('contact_id')->after('customer_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('gdns', function (Blueprint $table) {
            $table->foreignId('contact_id')->after('customer_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('proforma_invoices', function (Blueprint $table) {
            $table->foreignId('contact_id')->after('customer_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('reservations', function (Blueprint $table) {
            $table->foreignId('contact_id')->after('customer_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->foreignId('contact_id')->after('supplier_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->foreignId('contact_id')->after('supplier_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
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
