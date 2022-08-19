<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->string('cash_payed_type')->after('payment_type');
            $table->decimal('cash_payed', 22)->after('cash_payed_type');
            $table->dateTime('due_date')->nullable()->after('cash_payed');
        });
    }

    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('cash_payed_type');
            $table->dropColumn('cash_payed');
            $table->dropColumn('due_date');
        });
    }
};
