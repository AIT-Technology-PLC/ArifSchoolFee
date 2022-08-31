<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->decimal('freight_cost', 22)->nullable()->after('exchange_rate');
            $table->decimal('freight_insurance_cost', 22)->nullable()->after('freight_cost');
            $table->string('freight_unit')->nullable()->after('freight_insurance_cost');
        });
    }

    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('freight_cost');
            $table->dropColumn('freight_insurance_cost');
            $table->dropColumn('freight_unit');
        });
    }
};
