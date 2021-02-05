<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->nullable()->unsigned();
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->string('code')->unique();
            $table->string('status');
            $table->longText('description')->nullable();
            $table->dateTime('issued_on')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::create('transfer_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('transfer_id')->nullable()->unsigned();
            $table->bigInteger('warehouse_id')->nullable()->unsigned();
            $table->bigInteger('to_warehouse_id')->nullable()->unsigned();
            $table->bigInteger('product_id')->nullable()->unsigned();
            $table->decimal('quantity', 22);
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('transfer_id');

            $table->foreign('transfer_id')->references('id')->on('transfers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('to_warehouse_id')->references('id')->on('warehouses')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::drop('transfers');
        Schema::drop('transfer_details');
    }
}
