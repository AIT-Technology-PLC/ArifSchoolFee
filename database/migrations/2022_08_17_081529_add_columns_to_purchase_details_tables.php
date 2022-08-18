<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('purchase_details', function (Blueprint $table) {
            $table->decimal('freight_cost', 22)->nullable()->after('unit_price');
            $table->decimal('freight_insurance_cost', 22)->nullable()->after('freight_cost');
            $table->decimal('duty_rate', 22)->nullable()->after('freight_insurance_cost');
            $table->decimal('excise_tax', 22)->nullable()->after('duty_rate');
            $table->decimal('vat_rate', 22)->nullable()->after('excise_tax');
            $table->decimal('surtax', 22)->nullable()->after('vat_rate');
            $table->decimal('with_holding_tax', 22)->nullable()->after('surtax');
        });
    }

    public function down()
    {
        Schema::table('purchase_details', function (Blueprint $table) {
            $table->dropColumn('freight_cost');
            $table->dropColumn('freight_insurance_cost');
            $table->dropColumn('duty_rate');
            $table->dropColumn('excise_tax');
            $table->dropColumn('vat_rate');
            $table->dropColumn('surtax');
            $table->dropColumn('with_holding_tax');
        });
    }
};
