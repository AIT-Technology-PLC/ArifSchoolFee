<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProformaInvoicesTable extends Migration
{
    public function up()
    {
        Schema::create('proforma_invoices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('company_id')->nullable()->unsigned();
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->bigInteger('approved_by')->nullable()->unsigned();
            $table->bigInteger('executed_by')->nullable()->unsigned();
            $table->bigInteger('customer_id')->nullable()->unsigned();
            $table->string('code')->unique();
            $table->boolean('is_pending');
            $table->longText('terms')->nullable();
            $table->dateTime('expires_on')->nullable();
            $table->dateTime('issued_on')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('customer_id');

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('executed_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::create('proforma_invoice_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('proforma_invoice_id')->nullable()->unsigned();
            $table->bigInteger('product_id')->nullable()->unsigned();
            $table->decimal('quantity', 22);
            $table->decimal('unit_price', 22);
            $table->decimal('discount', 22);
            $table->timestamps();
            $table->softDeletes();

            $table->index('proforma_invoice_id');

            $table->foreign('proforma_invoice_id')->references('id')->on('proforma_invoices')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
        });
        
    }

    public function down()
    {
        Schema::drop('proforma_invoice_details');
        Schema::drop('proforma_invoices');
    }
}
