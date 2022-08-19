<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->string('tax_type')->nullable()->after('payment_type');
            $table->string('currency')->nullable()->after('tax_type');
            $table->decimal('exchange_rate', 22)->nullable()->after('currency');
        });
    }

    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('tax_type');
            $table->dropColumn('currency');
            $table->dropColumn('exchange_rate');
        });
    }
};
