<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_tender_checklists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->nullable()->unsigned();
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->string('item');
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::create('tender_statuses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->nullable()->unsigned();
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->string('status');
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::create('tenders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('customer_id')->nullable()->unsigned();
            $table->bigInteger('company_id')->nullable()->unsigned();
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->string('code')->nullable();
            $table->string('type');
            $table->string('status');
            $table->string('bid_bond_amount', 22)->nullable();
            $table->bigInteger('bid_bond_validity')->nullable();
            $table->string('bid_bond_type')->nullable();
            $table->bigInteger('participants');
            $table->dateTime('published_on')->nullable();
            $table->dateTime('closing_date')->nullable();
            $table->dateTime('opening_date')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('customer_id');

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::create('tender_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('tender_id')->nullable()->unsigned();
            $table->bigInteger('product_id')->nullable()->unsigned();
            $table->decimal('quantity', 22);
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('tender_id');
            $table->index('product_id');

            $table->foreign('tender_id')->references('id')->on('tenders')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');

        });

        Schema::create('tender_checklists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('tender_id')->nullable()->unsigned();
            $table->string('item');
            $table->string('status')->nullable();
            $table->string('comment')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('tender_id');

            $table->foreign('tender_id')->references('id')->on('tenders')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('general_tender_checklists');
        Schema::drop('tender_statuses');
        Schema::drop('tender_checklists');
        Schema::drop('tender_details');
        Schema::drop('tenders');
    }
}
