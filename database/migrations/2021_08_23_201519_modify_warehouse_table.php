<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyWarehouseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouses', function (Blueprint $table) {
            $table->boolean('is_sales_store')->default(1)->after('location');
            $table->boolean('can_be_sold_from')->default(1)->after('is_sales_store');
            $table->string('email')->nullable()->after('can_be_sold_from');
            $table->string('phone')->nullable()->after('email');
            $table->string('address')->nullable()->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropColumn([
                'is_sales_store',
                'can_be_sold_from',
                'email',
                'phone',
                'address',
            ]);
        });
    }
}
