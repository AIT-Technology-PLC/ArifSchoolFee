<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTinColumnToCustomersAndSuppliers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('tin')->after('company_name')->nullable();
            $table->string('address')->after('tin')->nullable();
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('tin')->after('company_name')->nullable();
            $table->string('address')->after('tin')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['tin', 'address']);
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn(['tin', 'address']);
        });
    }
}
