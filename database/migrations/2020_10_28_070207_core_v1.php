<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CoreV1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Users
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->dateTime('last_online_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        // Password Resets
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Failed Jobs - Queue
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        // Permissions
        Schema::create('permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('role');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Plans
        Schema::create('plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Companies
        Schema::create('companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('plan_id')->nullable()->unsigned();
            $table->string('name');
            $table->string('sector')->nullable();
            $table->string('membership_plan')->nullable();
            $table->boolean('enabled');
            $table->string('currency');
            $table->timestamps();
            $table->softDeletes();

            $table->index('plan_id');

            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade')->onUpdate('cascade');
        });

        // Employees
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->nullable()->unsigned();
            $table->bigInteger('company_id')->nullable()->unsigned();
            $table->bigInteger('permission_id')->nullable()->unsigned();
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->string('position');
            $table->boolean('enabled');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('permission_id');
            $table->index('user_id');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });

        // Warehouses
        Schema::create('warehouses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->nullable()->unsigned();
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->string('name');
            $table->string('location');
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });

        // Suppliers
        Schema::create('suppliers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->nullable()->unsigned();
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->string('company_name');
            $table->string('contact_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('country')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });

        // Product Categories
        Schema::create('product_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->nullable()->unsigned();
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->string('name');
            $table->json('properties')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });

        // Products
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_category_id')->nullable()->unsigned();
            $table->bigInteger('company_id')->nullable()->unsigned();
            $table->bigInteger('supplier_id')->nullable()->unsigned();
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->string('name');
            $table->string('type');
            $table->string('code')->nullable();
            $table->string('unit_of_measurement');
            $table->decimal('selling_price', 22)->nullable();
            $table->decimal('purchase_price', 22)->nullable();
            $table->decimal('min_on_hand', 22);
            $table->json('properties')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('product_category_id');
            $table->index('company_id');
            $table->index('supplier_id');

            $table->foreign('product_category_id')->references('id')->on('product_categories')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });

        // Product Images
        Schema::create('product_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id')->nullable()->unsigned();
            $table->string('name');
            $table->string('original_name');
            $table->timestamps();
            $table->softDeletes();

            $table->index('product_id');

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
        });

        // Purchases
        Schema::create('purchases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->nullable()->unsigned();
            $table->bigInteger('supplier_id')->nullable()->unsigned();
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->string('purchase_no')->unique();
            $table->string('payment_type');
            $table->string('status')->nullable();
            $table->boolean('is_manual');
            $table->dateTime('purchased_on')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('supplier_id');

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });

        // Purchase Details
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('purchase_id')->nullable()->unsigned();
            $table->bigInteger('product_id')->nullable()->unsigned();
            $table->bigInteger('warehouse_id')->nullable()->unsigned();
            $table->decimal('quantity', 22);
            $table->decimal('unit_price', 22);
            $table->timestamps();
            $table->softDeletes();

            $table->index('purchase_id');
            $table->index('product_id');

            $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade')->onUpdate('cascade');
        });

        // Customers
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->nullable()->unsigned();
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->string('company_name');
            $table->string('contact_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('country')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });

        // Sales
        Schema::create('sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->nullable()->unsigned();
            $table->bigInteger('customer_id')->nullable()->unsigned();
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->string('receipt_no')->unique();
            $table->string('payment_type');
            $table->string('status')->nullable();
            $table->boolean('is_manual');
            $table->dateTime('sold_on')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('customer_id');

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set ')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });

        // Sale Details
        Schema::create('sale_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sale_id')->nullable()->unsigned();
            $table->bigInteger('product_id')->nullable()->unsigned();
            $table->bigInteger('warehouse_id')->nullable()->unsigned();
            $table->decimal('quantity', 22);
            $table->decimal('unit_price', 22);
            $table->timestamps();
            $table->softDeletes();

            $table->index('sale_id');
            $table->index('product_id');

            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade')->onUpdate('cascade');
        });

        // Merchandise Products
        Schema::create('merchandises', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id')->nullable()->unsigned();
            $table->bigInteger('company_id')->nullable()->unsigned();
            $table->bigInteger('warehouse_id')->nullable()->unsigned();
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->decimal('total_received', 22);
            $table->decimal('total_on_hand', 22);
            $table->decimal('total_sold', 22);
            $table->decimal('total_transfer', 22);
            $table->decimal('total_broken', 22);
            $table->decimal('total_returns', 22);
            $table->dateTime('expires_on')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('product_id');
            $table->index('company_id');
            $table->index('warehouse_id');

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });

        // Manufacturing Products
        Schema::create('manufacturings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id')->nullable()->unsigned();
            $table->bigInteger('company_id')->nullable()->unsigned();
            $table->bigInteger('warehouse_id')->nullable()->unsigned();
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->decimal('total_products', 22);
            $table->decimal('total_in_process', 22);
            $table->decimal('total_on_hand', 22);
            $table->decimal('total_sold', 22);
            $table->decimal('total_broken', 22);
            $table->decimal('total_returns', 22);
            $table->string('status');
            $table->dateTime('started_on')->nullable();
            $table->dateTime('finishes_on')->nullable();
            $table->dateTime('expires_on')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('product_id');
            $table->index('company_id');
            $table->index('warehouse_id');

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });

        // Raw Material
        Schema::create('raw_materials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id')->nullable()->unsigned();
            $table->bigInteger('company_id')->nullable()->unsigned();
            $table->bigInteger('warehouse_id')->nullable()->unsigned();
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->decimal('total_received', 22);
            $table->decimal('total_used', 22);
            $table->decimal('total_on_hand', 22);
            $table->decimal('total_broken', 22);
            $table->dateTime('expires_on')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('product_id');
            $table->index('company_id');
            $table->index('warehouse_id');

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });

        // Bill of Materials
        Schema::create('bill_of_materials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id')->nullable()->unsigned();
            $table->bigInteger('company_id')->nullable()->unsigned();
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->json('materials');
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('product_id');
            $table->index('company_id');

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });

        // MRO Items
        Schema::create('mro_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id')->nullable()->unsigned();
            $table->bigInteger('company_id')->nullable()->unsigned();
            $table->bigInteger('warehouse_id')->nullable()->unsigned();
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->decimal('total_received', 22);
            $table->decimal('total_used', 22);
            $table->decimal('total_on_hand', 22);
            $table->decimal('total_broken', 22);
            $table->dateTime('expires_on')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('product_id');
            $table->index('company_id');
            $table->index('warehouse_id');

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
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
        Schema::drop('password_resets');
        Schema::drop('failed_jobs');
        Schema::drop('permissions');
        Schema::drop('plans');
        Schema::drop('companies');
        Schema::drop('employees');
        Schema::drop('warehouses');
        Schema::drop('suppliers');
        Schema::drop('product_categories');
        Schema::drop('products');
        Schema::drop('product_images');
        Schema::drop('purchases');
        Schema::drop('purchase_details');
        Schema::drop('customers');
        Schema::drop('sales');
        Schema::drop('sale_details');
        Schema::drop('merchandises');
        Schema::drop('manufacturings');
        Schema::drop('raw_materials');
        Schema::drop('bill_of_materials');
        Schema::drop('mro_items');
    }
}
