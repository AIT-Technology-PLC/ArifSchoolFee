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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->dateTime('last_online_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_enabled')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('limits', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('limitables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('limit_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('limitable_id');
            $table->string('limitable_type');
            $table->bigInteger('amount');

            $table->unique(['limit_id', 'limitable_id', 'limitable_type']);
        });

        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('is_enabled')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('featurables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feature_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('featurable_id');
            $table->string('featurable_type');
            $table->boolean('is_enabled')->default(1);

            $table->unique(['feature_id', 'featurable_id', 'featurable_type']);
        });

        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('logo')->nullable();
            $table->string('sector')->nullable();
            $table->string('sales_report_source')->nullable();
            $table->boolean('enabled');
            $table->string('currency');
            $table->string('tin')->unique()->nullable();
            $table->string('proforma_invoice_prefix')->nullable();
            $table->boolean('is_price_before_vat')->default(1);
            $table->boolean('is_discount_before_vat')->default(1);
            $table->boolean('is_convert_to_siv_as_approved')->default(1);
            $table->boolean('is_editing_reference_number_enabled')->default(1);
            $table->boolean('can_show_branch_detail_on_print')->default(1);
            $table->decimal('paid_time_off_amount', 22)->default(0);
            $table->string('paid_time_off_type')->default('Days');
            $table->bigInteger('working_days')->default(26);
            $table->boolean('is_backorder_enabled')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->index('plan_id');
        });

        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->string('name');
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['company_id', 'name']);
            $table->index('company_id');
        });

        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->string('position');
            $table->boolean('enabled');
            $table->string('gender');
            $table->string('address');
            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('tin_number')->nullable();
            $table->string('job_type');
            $table->string('phone');
            $table->string('id_type')->nullable();
            $table->string('id_number')->nullable();
            $table->dateTime('date_of_hiring')->nullable();
            $table->dateTime('date_of_birth')->nullable();
            $table->string('emergency_name')->nullable();
            $table->string('emergency_phone')->nullable();
            $table->decimal('paid_time_off_amount', 22)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('user_id');
        });

        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->string('name');
            $table->string('location');
            $table->boolean('is_active');
            $table->boolean('is_sales_store')->default(1);
            $table->boolean('can_be_sold_from')->default(1);
            $table->string('pos_provider')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('warehouse_id')->nullable()->after('id')->constrained()->onDelete('set null')->onUpdate('cascade');
        });

        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->string('company_name');
            $table->string('tin')->nullable();
            $table->string('address')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('country')->nullable();
            $table->decimal('debt_amount_limit', 22);
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->unique(['company_id', 'tin']);
        });

        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->string('name');
            $table->json('properties')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('product_category_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('brand_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->string('name');
            $table->string('type');
            $table->string('code')->nullable();
            $table->string('unit_of_measurement');
            $table->decimal('min_on_hand', 22);
            $table->string('is_batchable')->nullable();
            $table->string('batch_priority')->nullable();
            $table->boolean('is_active')->default(1);
            $table->boolean('is_active_for_sale')->default(1);
            $table->boolean('is_active_for_purchase')->default(1);
            $table->boolean('is_active_for_job')->default(1);
            $table->json('properties')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('product_category_id');
            $table->index('company_id');
            $table->index('supplier_id');
        });

        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('purchased_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('contact_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->bigInteger('code');
            $table->boolean('is_closed')->default(0);
            $table->string('type');
            $table->string('payment_type');
            $table->string('cash_paid_type');
            $table->decimal('cash_paid', 22);
            $table->dateTime('due_date')->nullable();
            $table->string('tax_type')->nullable();
            $table->string('currency')->nullable();
            $table->decimal('exchange_rate', 22)->nullable();
            $table->decimal('freight_cost', 22)->nullable();
            $table->decimal('freight_insurance_cost', 22)->nullable();
            $table->string('freight_unit')->nullable();
            $table->decimal('other_costs', 22)->default(0.00);
            $table->dateTime('purchased_on')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('supplier_id');
            $table->unique(['company_id', 'warehouse_id', 'code']);
        });

        Schema::create('purchase_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('quantity', 22);
            $table->decimal('unit_price', 22);
            $table->decimal('amount', 22)->nullable();
            $table->decimal('duty_rate', 22)->nullable();
            $table->decimal('excise_tax', 22)->nullable();
            $table->decimal('vat_rate', 22)->nullable();
            $table->decimal('surtax', 22)->nullable();
            $table->decimal('withholding_tax', 22)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('purchase_id');
            $table->index('product_id');
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->string('company_name');
            $table->string('tin')->nullable();
            $table->string('address')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('country')->nullable();
            $table->decimal('credit_amount_limit', 22);
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->unique(['company_id', 'tin']);
        });

        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('contact_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->bigInteger('code');
            $table->bigInteger('fs_number')->nullable();
            $table->string('payment_type');
            $table->string('cash_received_type');
            $table->decimal('cash_received', 22);
            $table->dateTime('issued_on')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->longText('description')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('reference_number')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('customer_id');
            $table->unique(['company_id', 'warehouse_id', 'code']);
            $table->unique(['company_id', 'warehouse_id', 'fs_number']);
        });

        Schema::create('sale_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('quantity', 22);
            $table->decimal('unit_price', 22);
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('sale_id');
            $table->index('product_id');
        });

        Schema::create('merchandises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('available', 22)->default(0.00);
            $table->decimal('reserved', 22)->default(0.00);
            $table->decimal('wip', 22)->default(0.00);
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('product_id');
            $table->index('warehouse_id');

            $table->unique(['product_id', 'warehouse_id']);
        });

        Schema::create('gdns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('sale_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('contact_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('subtracted_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->bigInteger('code');
            $table->boolean('is_closed')->default(0);
            $table->boolean('is_converted_to_sale')->default(0);
            $table->string('discount')->nullable();
            $table->string('payment_type');
            $table->string('cash_received_type');
            $table->decimal('cash_received', 22);
            $table->longText('description')->nullable();
            $table->dateTime('issued_on')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('reference_number')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('sale_id');
            $table->unique(['company_id', 'warehouse_id', 'code']);
        });

        Schema::create('gdn_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gdn_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('quantity', 22);
            $table->decimal('unit_price', 22)->nullable();
            $table->string('discount')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('gdn_id');
        });

        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('subtracted_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('added_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('transferred_from')->nullable()->constrained('warehouses')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('transferred_to')->nullable()->constrained('warehouses')->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('code');
            $table->boolean('is_closed')->default(0);
            $table->longText('description')->nullable();
            $table->dateTime('issued_on')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->unique(['company_id', 'warehouse_id', 'code']);
        });

        Schema::create('transfer_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transfer_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('quantity', 22);
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('transfer_id');
        });

        Schema::create('grns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('purchase_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('added_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->bigInteger('code');
            $table->longText('description')->nullable();
            $table->dateTime('issued_on')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('purchase_id');
            $table->unique(['company_id', 'warehouse_id', 'code']);
        });

        Schema::create('grn_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grn_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('quantity', 22);
            $table->string('batch_no')->nullable();
            $table->date('expiry_date')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('grn_id');
        });

        Schema::create('tender_checklist_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->string('name');
            $table->boolean('is_sensitive');
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->unique(['name', 'company_id']);
        });

        Schema::create('general_tender_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('tender_checklist_type_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->string('item');
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::create('tender_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->string('status');
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::create('tender_opportunities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('tender_status_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->string('code');
            $table->string('source');
            $table->dateTime('published_on')->nullable();
            $table->longText('body');
            $table->string('address')->nullable();
            $table->string('currency')->nullable();
            $table->decimal('price', 22)->nullable();
            $table->longText('comments')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('warehouse_id');
        });

        Schema::create('tenders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->string('code')->nullable();
            $table->string('type');
            $table->string('status');
            $table->string('bid_bond_amount', 22)->nullable();
            $table->bigInteger('bid_bond_validity')->nullable();
            $table->string('bid_bond_type')->nullable();
            $table->longText('price')->nullable();
            $table->longText('payment_term')->nullable();
            $table->bigInteger('participants')->nullable();
            $table->dateTime('published_on')->nullable();
            $table->dateTime('closing_date')->nullable();
            $table->dateTime('opening_date')->nullable();
            $table->longText('financial_reading')->nullable();
            $table->longText('technical_reading')->nullable();
            $table->dateTime('clarify_on')->nullable();
            $table->dateTime('visit_on')->nullable();
            $table->dateTime('premeet_on')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('customer_id');
        });

        Schema::create('tender_lots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tender_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tender_lot_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tender_lot_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('quantity', 22);
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('tender_lot_id');
            $table->index('product_id');
        });

        Schema::create('tender_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tender_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('general_tender_checklist_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->string('status')->nullable();
            $table->longText('comment')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('tender_id');
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('sivs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->bigInteger('code');
            $table->string('purpose')->nullable();
            $table->string('ref_num')->nullable();
            $table->longText('description')->nullable();
            $table->string('issued_to')->nullable();
            $table->string('delivered_by')->nullable();
            $table->string('received_by')->nullable();
            $table->dateTime('issued_on')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->unique(['company_id', 'warehouse_id', 'code']);
        });

        Schema::create('siv_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siv_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('quantity', 22);
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('siv_id');
        });

        Schema::create('proforma_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('converted_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('contact_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->string('prefix')->nullable();
            $table->bigInteger('code');
            $table->boolean('is_closed')->default(0);
            $table->string('discount')->nullable();
            $table->boolean('is_pending');
            $table->longText('terms')->nullable();
            $table->dateTime('expires_on')->nullable();
            $table->dateTime('issued_on')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('customer_id');
            $table->unique(['company_id', 'warehouse_id', 'code']);
        });

        Schema::create('proforma_invoice_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proforma_invoice_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('custom_product')->nullable();
            $table->longText('specification')->nullable();
            $table->decimal('quantity', 22);
            $table->decimal('unit_price', 22);
            $table->decimal('discount', 22)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('proforma_invoice_id');
        });

        Schema::create('damages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('subtracted_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->bigInteger('code');
            $table->longText('description')->nullable();
            $table->dateTime('issued_on')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->unique(['company_id', 'warehouse_id', 'code']);
        });

        Schema::create('damage_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('damage_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('quantity', 22);
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('damage_id');
        });

        Schema::create('adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('adjusted_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->bigInteger('code');
            $table->longText('description')->nullable();
            $table->dateTime('issued_on')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->unique(['company_id', 'warehouse_id', 'code']);
        });

        Schema::create('adjustment_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('adjustment_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->boolean('is_subtract');
            $table->decimal('quantity', 22);
            $table->longText('reason');
            $table->timestamps();
            $table->softDeletes();

            $table->index('adjustment_id');
        });

        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('added_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->bigInteger('code');
            $table->longText('description')->nullable();
            $table->dateTime('issued_on')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('customer_id');
            $table->unique(['company_id', 'warehouse_id', 'code']);
        });

        Schema::create('return_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('quantity', 22);
            $table->decimal('unit_price', 22)->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('return_id');
        });

        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('contact_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('reserved_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('converted_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->nullableMorphs('reservable');
            $table->bigInteger('code');
            $table->string('discount')->nullable();
            $table->string('payment_type');
            $table->string('cash_received_type');
            $table->decimal('cash_received', 22);
            $table->longText('description')->nullable();
            $table->dateTime('issued_on')->nullable();
            $table->dateTime('expires_on')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('reference_number')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->unique(['company_id', 'warehouse_id', 'code']);
        });

        Schema::create('reservation_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('quantity', 22);
            $table->decimal('unit_price', 22)->nullable();
            $table->string('discount')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('reservation_id');
        });

        Schema::create('user_warehouse', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('type');
            $table->timestamps();

            $table->unique(['user_id', 'warehouse_id', 'type']);
        });

        Schema::create('credits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('gdn_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->bigInteger('code');
            $table->decimal('cash_amount', 22);
            $table->decimal('credit_amount', 22);
            $table->decimal('credit_amount_settled', 22);
            $table->longText('description')->nullable();
            $table->dateTime('issued_on')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->dateTime('last_settled_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('warehouse_id');
            $table->index('customer_id');
            $table->unique(['company_id', 'warehouse_id', 'code']);
        });

        Schema::create('credit_settlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('credit_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('amount', 22);
            $table->string('method');
            $table->string('bank_name')->nullable();
            $table->string('reference_number')->nullable();
            $table->dateTime('settled_at')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('credit_id');
        });

        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->unique()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('company_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->string('type');
            $table->decimal('min_price', 22)->nullable();
            $table->decimal('max_price', 22)->nullable();
            $table->decimal('fixed_price', 22)->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('company_id');
        });

        Schema::create('bill_of_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade')->after('company_id');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade')->after('updated_by');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('name');
            $table->boolean('is_active');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('product_id');
        });

        Schema::create('bill_of_material_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bill_of_material_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('quantity', 22);
            $table->timestamps();
            $table->softDeletes();

            $table->index('bill_of_material_id');
            $table->index('product_id');
        });

        Schema::create('job_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('factory_id')->nullable()->constrained('warehouses')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('closed_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->bigInteger('code');
            $table->boolean('is_internal_job');
            $table->longText('description')->nullable();
            $table->dateTime('issued_on')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('warehouse_id');
            $table->index('company_id');
        });

        Schema::create('job_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_order_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('bill_of_material_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('quantity', 22);
            $table->decimal('wip', 22)->default(0.00);
            $table->decimal('available', 22)->default(0.00);
            $table->timestamps();
            $table->softDeletes();

            $table->index('job_order_id');
            $table->index('product_id');
            $table->index('bill_of_material_id');
        });

        Schema::create('job_extras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_order_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('executed_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->decimal('quantity', 22);
            $table->string('type');
            $table->string('status')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('job_order_id');
            $table->index('product_id');
        });

        Schema::create('employee_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('code');
            $table->dateTime('issued_on')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::create('employee_transfer_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_transfer_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('employee_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes();

            $table->index('employee_transfer_id');
        });

        Schema::create('warnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('employee_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('code');
            $table->string('type');
            $table->dateTime('issued_on')->nullable();
            $table->longText('letter');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('warehouse_id');
        });

        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->bigInteger('code');
            $table->dateTime('issued_on')->nullable();
            $table->date('starting_period')->nullable();
            $table->date('ending_period')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['starting_period', 'ending_period', 'warehouse_id']);
            $table->index('company_id');
            $table->index('warehouse_id');
        });

        Schema::create('attendance_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('employee_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('days');
            $table->timestamps();
            $table->softDeletes();

            $table->index('attendance_id');
        });

        Schema::create('integrations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('is_enabled');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('company_integration', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('integration_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->boolean('is_enabled');
            $table->timestamps();
        });

        Schema::create('leave_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->unique(['company_id', 'name']);
        });

        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('employee_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('leave_category_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('code');
            $table->dateTime('starting_period')->nullable();
            $table->dateTime('ending_period')->nullable();
            $table->boolean('is_paid_time_off');
            $table->decimal('time_off_amount', 22)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('leave_category_id');
            $table->index('company_id');
            $table->index('warehouse_id');
        });

        Schema::create('advancements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->bigInteger('code');
            $table->dateTime('issued_on')->nullable();
            $table->string('type');
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('warehouse_id');
        });

        Schema::create('advancement_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advancement_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('employee_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('compensation_id')->nullable()->constrained('compensations')->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('amount', 22);
            $table->string('job_position');
            $table->timestamps();
            $table->softDeletes();

            $table->index('advancement_id');
        });

        Schema::create('expense_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('rejected_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('employee_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('code');
            $table->dateTime('issued_on')->nullable();

            $table->unique(['warehouse_id', 'code']);
            $table->index('company_id');
            $table->index('warehouse_id');
        });

        Schema::create('expense_claim_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expense_claim_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('item');
            $table->decimal('price', 22);
            $table->timestamps();
            $table->softDeletes();

            $table->index('expense_claim_id');
        });

        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->bigInteger('code');
            $table->string('title');
            $table->longText('content');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::create('announcement_warehouse', function (Blueprint $table) {
            $table->id();
            $table->foreignId('announcement_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');

            $table->index('announcement_id');
            $table->index('warehouse_id');
        });

        Schema::create('compensations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->string('name');
            $table->string('type');
            $table->boolean('is_active');
            $table->boolean('is_taxable');
            $table->boolean('is_adjustable');
            $table->boolean('can_be_inputted_manually');
            $table->decimal('percentage', 22)->nullable();
            $table->decimal('default_value', 22)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['name', 'company_id']);
            $table->index('company_id');
        });

        Schema::table('compensations', function (Blueprint $table) {
            $table->foreignId('depends_on')->nullable()->after('id')->constrained('compensations')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('employee_compensations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('compensation_id')->nullable()->constrained('compensations')->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('amount', 22);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['employee_id', 'compensation_id']);
            $table->index('compensation_id');
        });

        Schema::create('compensation_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->bigInteger('code');
            $table->dateTime('issued_on')->nullable();
            $table->date('starting_period')->nullable();
            $table->date('ending_period')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['company_id', 'starting_period']);
            $table->unique(['company_id', 'ending_period']);
            $table->index('company_id');
        });

        Schema::create('compensation_adjustment_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('adjustment_id')->nullable()->constrained('compensation_adjustments')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('employee_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('compensation_id')->nullable()->constrained('compensations')->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('amount', 22);
            $table->timestamps();
            $table->softDeletes();

            $table->index('adjustment_id');
        });

        Schema::create('merchandise_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchandise_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('batch_no')->nullable();
            $table->date('expiry_date')->nullable();
            $table->decimal('quantity', 22)->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('merchandise_id');
            $table->index('batch_no');
        });

        Schema::create('employee_compensation_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('compensation_id')->nullable()->constrained('compensations')->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('change_count');
            $table->decimal('amount', 22);
            $table->timestamps();
            $table->softDeletes();

            $table->index('employee_id');
        });

        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('purchase_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->bigInteger('code');
            $table->decimal('cash_amount', 22);
            $table->decimal('debt_amount', 22);
            $table->decimal('debt_amount_settled', 22);
            $table->longText('description')->nullable();
            $table->dateTime('issued_on')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->dateTime('last_settled_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('warehouse_id');
            $table->index('supplier_id');
            $table->unique(['company_id', 'warehouse_id', 'code']);
        });

        Schema::create('debt_settlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('debt_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('amount', 22);
            $table->string('method');
            $table->string('bank_name')->nullable();
            $table->string('reference_number')->nullable();
            $table->dateTime('settled_at')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('debt_id');
        });

        Schema::create('expense_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('warehouse_id');
            $table->unique(['warehouse_id', 'name']);
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('contact_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->bigInteger('code');
            $table->string('tax_type')->nullable();
            $table->dateTime('issued_on')->nullable();
            $table->bigInteger('reference_number')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('warehouse_id');
            $table->unique(['company_id', 'warehouse_id', 'code']);
        });

        Schema::create('expense_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expense_category_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('expense_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('name');
            $table->decimal('quantity', 22);
            $table->decimal('unit_price');
            $table->timestamps();
            $table->softDeletes();

            $table->index('expense_category_id');
            $table->index('expense_id');
        });

        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->string('name');
            $table->string('tin')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->unique(['company_id', 'tin']);
        });

        Schema::create('price_increments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->bigInteger('code');
            $table->string('target_product');
            $table->string('price_type');
            $table->decimal('price_increment', 22);
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::create('price_increment_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('price_increment_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes();

            $table->index('price_increment_id');
        });

        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->string('name');
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->unique(['company_id', 'name']);
        });

        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('paid_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->bigInteger('code');
            $table->string('bank_name');
            $table->dateTime('issued_on')->nullable();
            $table->date('starting_period')->nullable();
            $table->date('ending_period')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['starting_period', 'ending_period', 'warehouse_id']);
            $table->index('company_id');
            $table->index('warehouse_id');
        });

        Schema::create('job_detail_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_detail_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('quantity', 22)->default(0.00);
            $table->string('type');
            $table->timestamps();
            $table->softDeletes();

            $table->index('job_detail_id');
            $table->index('product_id');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
        Schema::drop('failed_jobs');
        Schema::drop('plans');
        Schema::drop('limitables');
        Schema::drop('limits');
        Schema::drop('companies');
        Schema::drop('departments');
        Schema::drop('employees');
        Schema::drop('warehouses');
        Schema::drop('suppliers');
        Schema::drop('product_categories');
        Schema::drop('products');
        Schema::drop('purchases');
        Schema::drop('purchase_details');
        Schema::drop('customers');
        Schema::drop('sales');
        Schema::drop('sale_details');
        Schema::drop('merchandises');
        Schema::drop('gdns');
        Schema::drop('gdn_details');
        Schema::drop('transfers');
        Schema::drop('transfer_details');
        Schema::drop('grns');
        Schema::drop('grn_details');
        Schema::drop('tender_checklist_types');
        Schema::drop('general_tender_checklists');
        Schema::drop('tender_statuses');
        Schema::drop('tender_opportunities');
        Schema::drop('tenders');
        Schema::drop('tender_lots');
        Schema::drop('tender_lot_details');
        Schema::drop('tender_checklists');
        Schema::drop('notifications');
        Schema::drop('sivs');
        Schema::drop('siv_details');
        Schema::drop('proforma_invoice_details');
        Schema::drop('proforma_invoices');
        Schema::drop('damage_details');
        Schema::drop('damages');
        Schema::drop('adjustment_details');
        Schema::drop('adjustments');
        Schema::drop('featurables');
        Schema::drop('features');
        Schema::drop('return_details');
        Schema::drop('returns');
        Schema::drop('reservation_details');
        Schema::drop('reservations');
        Schema::drop('user_warehouse');
        Schema::drop('credit_settlements');
        Schema::drop('credits');
        Schema::drop('prices');
        Schema::drop('bill_of_material_details');
        Schema::drop('bill_of_materials');
        Schema::drop('job_details');
        Schema::drop('job_extras');
        Schema::drop('job_orders');
        Schema::drop('employee_transfer_details');
        Schema::drop('employee_transfers');
        Schema::drop('warnings');
        Schema::drop('attendance_details');
        Schema::drop('attendances');
        Schema::drop('company_integration');
        Schema::drop('integrations');
        Schema::drop('leave_categories');
        Schema::drop('leaves');
        Schema::drop('advancement_details');
        Schema::drop('advancements');
        Schema::drop('expense_claim_details');
        Schema::drop('expense_claims');
        Schema::drop('announcements');
        Schema::drop('announcement_warehouse');
        Schema::drop('compensations');
        Schema::drop('employee_compensations');
        Schema::drop('compensation_adjustment_details');
        Schema::drop('compensation_adjustments');
        Schema::drop('merchandise_batches');
        Schema::drop('employee_compensation_histories');
        Schema::drop('debt_settlements');
        Schema::drop('debts');
        Schema::drop('expense_details');
        Schema::drop('expenses');
        Schema::drop('expense_categories');
        Schema::drop('contacts');
        Schema::drop('price_increment_details');
        Schema::drop('price_increments');
        Schema::drop('brands');
        Schema::drop('payrolls');
        Schema::drop('job_detail_histories');
    }
};