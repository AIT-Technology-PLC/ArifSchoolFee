<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProformaInvoicePrefixToCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('proforma_invoice_prefix')->nullable()->after('currency');
            $table->boolean('is_price_before_vat')->default(1)->after('proforma_invoice_prefix');
            $table->boolean('is_discount_before_vat')->default(1)->after('is_price_before_vat');
        });

        Schema::table('proforma_invoices', function (Blueprint $table) {
            $table->string('prefix')->nullable()->after('customer_id');
        });

        Schema::table('proforma_invoice_details', function (Blueprint $table) {
            $table->string('custom_product')->nullable()->after('proforma_invoice_id');
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
            $table->dropColumn(['proforma_invoice_prefix', 'is_price_before_vat', 'is_discount_before_vat']);
        });

        Schema::table('proforma_invoices', function (Blueprint $table) {
            $table->dropColumn(['prefix']);
        });

        Schema::table('proforma_invoice_details', function (Blueprint $table) {
            $table->dropColumn(['custom_product']);
        });
    }
}
