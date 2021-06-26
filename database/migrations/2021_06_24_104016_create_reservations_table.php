<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchandises', function (Blueprint $table) {
            $table->decimal('reserved', 22)->default(0.00)->after('on_hand');
            $table->renameColumn('on_hand', 'available');
        });

        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_id')->nullable()->unsigned();
            $table->bigInteger('company_id')->nullable()->unsigned();
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->bigInteger('approved_by')->nullable()->unsigned();
            $table->bigInteger('reserved_by')->nullable()->unsigned();
            $table->bigInteger('cancelled_by')->nullable()->unsigned();
            $table->bigInteger('converted_by')->nullable()->unsigned();
            $table->nullableMorphs('reservable');
            $table->string('code')->unique();
            $table->string('payment_type');
            $table->decimal('cash_received_in_percentage', 22);
            $table->longText('description')->nullable();
            $table->dateTime('issued_on')->nullable();
            $table->dateTime('expires_on')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('reserved_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('cancelled_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('converted_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::create('reservation_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('reservation_id')->nullable()->unsigned();
            $table->bigInteger('warehouse_id')->nullable()->unsigned();
            $table->bigInteger('product_id')->nullable()->unsigned();
            $table->decimal('quantity', 22);
            $table->decimal('unit_price', 22)->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('reservation_id');

            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchandises', function (Blueprint $table) {
            $table->renameColumn('available', 'on_hand');
            $table->dropColumn(['reserved']);
        });

        Schema::drop('reservation_details');

        Schema::drop('reservations');
    }
}
