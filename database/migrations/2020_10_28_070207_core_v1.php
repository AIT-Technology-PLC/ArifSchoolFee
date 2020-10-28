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
            $table->rememberToken();
            $table->timestamp('last_online_at')->nullable();
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
            $table->string('users');
            $table->string('settings');
            $table->string('warehouses');
            $table->string('products');
            $table->string('merchandises');
            $table->string('jobs');
            $table->string('raw_materials');
            $table->string('bill_of_materials');
            $table->string('mro_items');
            $table->timestamps();
            $table->softDeletes();
        });


        // Companies
        Schema::create('companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('sector');
            $table->string('employees_num');
            $table->string('city');
            $table->string('manager');
            $table->string('phone');
            $table->string('telephone');
            $table->string('membership_plan');
            $table->string('currency');
            $table->string('logo');
            $table->string('location');
            $table->longText('description');
            $table->timestamps();
            $table->softDeletes();
        });


        // Employees
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->bigInteger('company_id');
            $table->bigInteger('permission_id');
            $table->string('name');
            $table->string('gender');
            $table->string('position');
            $table->string('department');
            $table->string('city');
            $table->string('address');
            $table->string('phone');
            $table->longText('description');
            $table->timestamps();
            $table->softDeletes();
        });


        // Warehouses
        Schema::create('warehouses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('location');
            $table->longText('description');
            $table->timestamps();
            $table->softDeletes();
        });


        // Products
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id');
            $table->string('name');
            $table->string('selling_price');
            $table->string('purchase_price');
            $table->string('unit_of_measurement');
            $table->boolean('is_expirable');
            $table->json('properties');
            $table->longText('description');
            $table->timestamps();
            $table->softDeletes();
        });


        // Product Images
        Schema::create('product_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id');
            $table->string('name');
            $table->string('original_name');
            $table->timestamps();
            $table->softDeletes();
        });


        // Merchandise
        Schema::create('merchandises', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('merchandise_category_id');
            $table->bigInteger('product_id');
            $table->bigInteger('warehouse_id');
            $table->string('on_hand');
            $table->string('min_on_hand');
            $table->timestamp('expires_on')->nullable();
            $table->timestamp('brought_on')->nullable();
            $table->longText('description');
            $table->timestamps();
            $table->softDeletes();
        });


        // Merchandise Categories
        Schema::create('merchandise_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->json('properties');
            $table->longText('description');
            $table->timestamps();
            $table->softDeletes();
        });


        // Work In Process
        Schema::create('in_process_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id');
            $table->string('in_process');
            $table->string('progress');
            $table->timestamp('started_on')->nullable();
            $table->timestamp('finishes_on')->nullable();
            $table->longText('description');
            $table->timestamps();
            $table->softDeletes();
        });


        // Finished Products
        Schema::create('finished_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('in_process_product_id');
            $table->bigInteger('warehouse_id');
            $table->string('on_hand');
            $table->string('min_on_hand');
            $table->timestamp('expires_on')->nullable();
            $table->timestamp('brought_on')->nullable();
            $table->longText('description');
            $table->timestamps();
            $table->softDeletes();
        });


        // Raw Material
        Schema::create('raw_materials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id');
            $table->bigInteger('warehouse_id');
            $table->string('on_hand');
            $table->string('min_on_hand');
            $table->string('unit_of_measurement');
            $table->string('purchase_price');
            $table->json('properties');
            $table->timestamp('expires_on')->nullable();
            $table->longText('description');
            $table->timestamps();
            $table->softDeletes();
        });

        
        // Bill of Materials
        Schema::create('bill_of_materials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id');
            $table->json('materials');
            $table->longText('description');
            $table->timestamps();
            $table->softDeletes();
        });


        // MRO Items
        Schema::create('mro_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id');
            $table->string('name');
            $table->string('on_hand');
            $table->string('min_on_hand');
            $table->string('unit_of_measurement');
            $table->string('purchase_price');
            $table->json('properties');
            $table->timestamp('brought_on')->nullable();
            $table->timestamp('expires_on')->nullable();
            $table->longText('description');
            $table->timestamps();
            $table->softDeletes();
        });
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
        Schema::drop('companies');
        Schema::drop('employees');
        Schema::drop('warehouses');
        Schema::drop('products');
        Schema::drop('product_images');
        Schema::drop('merchandises');
        Schema::drop('merchandise_categories');
        Schema::drop('in_process_products');
        Schema::drop('finished_products');
        Schema::drop('raw_materials');
        Schema::drop('bill_of_materials');
        Schema::drop('mro_items');
    }
}
